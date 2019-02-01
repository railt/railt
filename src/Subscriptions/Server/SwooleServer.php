<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\Server;

use Illuminate\Contracts\Support\Jsonable;
use Railt\Foundation\ApplicationInterface;
use Railt\Subscriptions\Message\Message;
use Railt\Subscriptions\Message\MessageInterface;
use Railt\Subscriptions\Protocol;
use Railt\Subscriptions\Server;
use Railt\Subscriptions\SubProtocol\ProtocolInterface;
use Railt\Io\Readable;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server as WsServer;

/**
 * Class SwooleServer
 */
class SwooleServer implements ServerInterface
{
    /**
     * @var string
     */
    private const SEC_KEY_UUID = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';

    /**
     * @var string
     */
    private const SEC_KEY_FORMAT = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';

    /**
     * @var string
     */
    private const SWOOLE_VERSION_MIN = '4.1.0';

    /**
     * @var string
     */
    private const SWOOLE_VERSION_MAX = '5.0.0';

    /**
     * @var ApplicationInterface
     */
    protected $app;

    /**
     * @var Readable
     */
    protected $schema;

    /**
     * @var ProtocolInterface[]|array
     */
    protected $connections = [];

    /**
     * Server constructor.
     * @param ApplicationInterface $app
     * @param Readable $schema
     */
    public function __construct(ApplicationInterface $app, Readable $schema)
    {
        $this->app = $app;
        $this->schema = $schema;
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        if (! \defined('\\SWOOLE_VERSION')) {
            return false;
        }

        $min = \version_compare(\SWOOLE_VERSION, self::SWOOLE_VERSION_MIN);
        $max = \version_compare(\SWOOLE_VERSION, self::SWOOLE_VERSION_MAX);

        return $min !== -1 && $max === -1;
    }

    /**
     * @param string $host
     * @param int $port
     */
    public function run(string $host, int $port = 80): void
    {
        $server = new WsServer($host, $port);

        $server->on('handshake', function (Request $request, Response $response) use ($server) {
            return $this->onHandshake($server, $request, $response);
        });

        $server->on('message', function (WsServer $server, Frame $frame): void {
            $this->onMessage($server, $frame);
        });

        $server->on('close', function (WsServer $server, $fd): void {
            $this->onClose($server, $fd);
        });

        $server->start();
    }

    /**
     * @param WsServer $server
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    private function onHandshake(WsServer $server, Request $request, Response $response): bool
    {
        try {
            $protocol = $this->getProtocol($request);

            $this->addUpgradeHeaders($protocol, $request, $response);
            $this->addAnswerResponder($server, $protocol, $request->fd);
            $this->registerTickHandler($server, $protocol, $request->fd);

            $this->connections[$request->fd] = $protocol;
        } catch (\Throwable $e) {
            $response->status(426, $e->getMessage());
            $response->end();
        }

        $response->status(101);
        $response->end();

        return isset($this->connections[$request->fd]);
    }

    /**
     * @param Request $request
     * @return ProtocolInterface
     * @throws \LogicException
     */
    private function getProtocol(Request $request): ProtocolInterface
    {
        $protocol = (string)$request->header['sec-websocket-protocol'];

        return Protocol::resolve($protocol, $this->app->connect($this->schema));
    }

    /**
     * @param ProtocolInterface $protocol
     * @param Request $request
     * @param Response $response
     * @throws \InvalidArgumentException
     */
    private function addUpgradeHeaders(ProtocolInterface $protocol, Request $request, Response $response): void
    {
        $response->header('Upgrade', 'websocket');
        $response->header('Connection', 'Upgrade');
        $response->header('Sec-WebSocket-Version', '13');
        $response->header('Sec-WebSocket-Accept', $this->readSecWebSocketKey($request));
        $response->header('Sec-WebSocket-Protocol', $protocol::getName());
    }

    /**
     * @param Request $request
     * @return string
     * @throws \InvalidArgumentException
     */
    private function readSecWebSocketKey(Request $request): string
    {
        $key = $request->header['sec-websocket-key'];

        $this->matchSecWebSocketKey((string)$key);

        return \base64_encode(\sha1($key . self::SEC_KEY_UUID, true));
    }

    /**
     * @param string $key
     * @return bool
     * @throws \InvalidArgumentException
     */
    private function matchSecWebSocketKey(string $key): bool
    {
        if (\preg_match(self::SEC_KEY_FORMAT, $key) === 0) {
            throw new \InvalidArgumentException('Bad format of Sec-WebSocket-Key');
        }

        if (! \is_string($decodedKey = \base64_decode($key))) {
            throw new \InvalidArgumentException('Bad format of Sec-WebSocket-Key');
        }

        if (\strlen($decodedKey) !== 16) {
            throw new \InvalidArgumentException('Bad length of Sec-WebSocket-Key');
        }

        return true;
    }

    /**
     * @param WsServer $server
     * @param ProtocolInterface $protocol
     * @param int $fd
     */
    private function addAnswerResponder(WsServer $server, ProtocolInterface $protocol, int $fd): void
    {
        $protocol->onAnswer(function (MessageInterface $message) use ($server, $fd): void {
            /** @var MessageInterface|Jsonable $message */
            $server->push($fd, $message->toJson($this->getJsonFlags()));
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
     * @param WsServer $server
     * @param Frame $frame
     * @throws \Throwable
     */
    private function onMessage(WsServer $server, Frame $frame): void
    {
        if ($frame->opcode === 1 && $message = $this->toMessage((string)$frame->data)) {
            $connection = $this->connections[$frame->fd];

            try {
                $connection->handle($message);
            } catch (\Throwable $e) {
                $this->onError($server, $frame->fd, $e);
            }
        }
    }

    /**
     * @param WsServer $server
     * @param int $fd
     * @param \Throwable $error
     * @throws \Throwable
     */
    private function onError(WsServer $server, int $fd, \Throwable $error): void
    {
        $server->close($fd);

        if ($this->app->isDebug()) {
            $server->push($fd, (string)$error);
        }
    }

    /**
     * @param WsServer $server
     * @param int $fd
     */
    private function onClose(WsServer $server, int $fd): void
    {
        if ($this->hasConnection($fd)) {
            $this->connections[$fd]->close();

            unset($this->connections[$fd]);
        }
    }

    /**
     * @param int $id
     * @return bool
     */
    private function hasConnection(int $id): bool
    {
        return isset($this->connections[$id]);
    }

    /**
     * @param WsServer $server
     * @param int $fd
     * @param ProtocolInterface $protocol
     */
    private function registerTickHandler(WsServer $server, ProtocolInterface $protocol, int $fd): void
    {
        $server->tick(10000, function ($timer) use ($protocol, $fd, $server): void {
            if (! isset($this->connections[$fd])) {
                $server->clearTimer($timer);
                return;
            }

            $protocol->notify();
        });
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
