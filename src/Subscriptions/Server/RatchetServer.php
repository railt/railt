<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\Server;

use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Io\Readable;
use Railt\Subscriptions\Server\ReactServer\FrontController;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\Server;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class RatchetServer
 */
class RatchetServer implements ServerInterface
{
    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var ApplicationInterface
     */
    protected $app;

    /**
     * @var Readable
     */
    protected $schema;

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
        return \class_exists(WsServer::class);
    }

    /**
     * @param LoopInterface $loop
     * @return RatchetServer|$this
     */
    public function withEventLoop(LoopInterface $loop): self
    {
        $this->loop = $loop;

        return $this;
    }

    /**
     * @param string $host
     * @param int $port
     * @throws \BadMethodCallException
     * @throws \DomainException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    public function run(string $host, int $port = 80): void
    {
        $loop = $this->getEventLoop();

        $controller = new FrontController($loop, $this->app, $this->schema);

        $this->onConnectionClosed($controller);

        $protocol = new HttpServer(new WsServer($controller));
        $server = new IoServer($protocol, $this->socket($loop, $host, $port), $loop);

        $server->run();
    }

    /**
     * @return LoopInterface
     * @throws \BadMethodCallException
     */
    public function getEventLoop(): LoopInterface
    {
        if ($this->loop === null) {
            $this->loop = Factory::create();
        }

        return $this->loop;
    }

    /**
     * @param FrontController $controller
     */
    private function onConnectionClosed(FrontController $controller): void
    {
        $handler = function (ConnectionClosed $event) use ($controller): void {
            $controller->close($event->getConnection());
        };

        $this->getEventDispatcher()->addListener(ConnectionClosed::class, $handler);
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->app->getContainer()->make(EventDispatcherInterface::class);
    }

    /**
     * @param LoopInterface $loop
     * @param string $host
     * @param int $port
     * @return Server
     * @throws \BadMethodCallException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    private function socket(LoopInterface $loop, string $host, int $port = 80): Server
    {
        return new Server(\sprintf('%s:%s', $host, $port), $loop);
    }
}
