<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\Server\ReactServer;

use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Support\Jsonable;
use Railt\Foundation\ApplicationInterface;
use Railt\Subscriptions\Message\Message;
use Railt\Subscriptions\Message\MessageInterface;
use Railt\Subscriptions\Protocol;
use Railt\Subscriptions\Server;
use Railt\Subscriptions\SubProtocol\ProtocolInterface;
use Railt\Http\Identifiable;
use Railt\Io\Readable;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\WebSocket\WsConnection;
use Ratchet\WebSocket\WsServerInterface;
use React\EventLoop\LoopInterface;

/**
 * Class FrontController
 */
class FrontController implements MessageComponentInterface, WsServerInterface
{
    /**
     * Regular behaviour
     * @var int
     */
    public const CLOSE_REASON_DEFAULT = 0x00;

    /**
     * Internal Server Error
     * @var int
     */
    public const CLOSE_REASON_INTERNAL_ERROR = 0x01;

    /**
     * Bad Request
     * @var int
     */
    public const CLOSE_REASON_CLIENT_ERROR = 0x02;

    /**
     * @var ApplicationInterface
     */
    private $app;

    /**
     * @var Readable
     */
    private $schema;

    /**
     * @var \SplObjectStorage|ProtocolInterface[]|ConnectionInterface[]
     */
    private $connections;

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * FrontController constructor.
     * @param LoopInterface $loop
     * @param ApplicationInterface $app
     * @param Readable $schema
     */
    public function __construct(LoopInterface $loop, ApplicationInterface $app, Readable $schema)
    {
        $this->app = $app;
        $this->loop = $loop;
        $this->schema = $schema;

        $this->connections = new \SplObjectStorage();
        $this->registerPingNotifiers();
    }

    /**
     * @return void
     */
    private function registerPingNotifiers(): void
    {
        $this->loop->addPeriodicTimer(10, function (): void {
            foreach ($this->connections as $connection) {
                $this->connections[$connection]->notify();
            }
        });
    }

    /**
     * @param Identifiable $connection
     */
    public function close(Identifiable $connection): void
    {
        foreach ($this->connections as $conn) {
            $protocol = $this->connections[$conn];

            if ($protocol->getId() === $connection->getId()) {
                $conn->close(self::CLOSE_REASON_DEFAULT);
            }
        }
    }

    /**
     * @return array
     */
    public function getSubProtocols(): array
    {
        return Protocol::all();
    }

    /**
     * @param ConnectionInterface|WsConnection $connection
     */
    public function onOpen(ConnectionInterface $connection): void
    {
        try {
            /** @var Request $request */
            $request = $connection->httpRequest;

            $protocol = $this->getSubProtocol($request);

            $this->addAnswerResponder($protocol, $connection);

            $this->connections[$connection] = $protocol;
        } catch (\Throwable $e) {
            $connection->close(self::CLOSE_REASON_INTERNAL_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return ProtocolInterface
     * @throws \LogicException
     */
    private function getSubProtocol(Request $request): ProtocolInterface
    {
        $protocol = $request->getHeaderLine('Sec-WebSocket-Protocol');

        return Protocol::resolve($protocol, $this->app->connect($this->schema));
    }

    /**
     * @param ProtocolInterface $protocol
     * @param ConnectionInterface $connection
     */
    private function addAnswerResponder(ProtocolInterface $protocol, ConnectionInterface $connection): void
    {
        $protocol->onAnswer(function (MessageInterface $message) use ($connection): void {
            /** @var Jsonable $message */
            $connection->send($message->toJson($this->getJsonFlags()));
        });
    }

    /**
     * @return int
     */
    private function getJsonFlags(): int
    {
        return Server::getJsonFlags($this->app->isDebug());
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onClose(ConnectionInterface $connection): void
    {
        if ($this->connections->contains($connection)) {
            $lifecycle = $this->connections[$connection];

            $lifecycle->close(self::CLOSE_REASON_INTERNAL_ERROR);

            $this->connections->detach($connection);
        }
    }

    /**
     * @param ConnectionInterface $connection
     * @param \Exception $error
     */
    public function onError(ConnectionInterface $connection, \Exception $error): void
    {
        $connection->close();
    }

    /**
     * @param ConnectionInterface $from
     * @param string $body
     */
    public function onMessage(ConnectionInterface $from, $body): void
    {
        $lifecycle = $this->connections[$from];

        $lifecycle->handle($this->toMessage($body));
    }

    /**
     * @param string|mixed $body
     * @return MessageInterface|null
     */
    private function toMessage($body): ?MessageInterface
    {
        if (! \is_string($body)) {
            return null;
        }

        $data = \json_decode($body, true);

        if (\json_last_error() !== \JSON_ERROR_NONE) {
            return null;
        }

        return new Message($data);
    }
}
