<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Foundation\Http;

use Railt\Container\Container;
use Railt\Foundation\HttpKernel;
use Railt\SDL\Document\Document;
use Railt\Container\ContainerInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Contracts\GraphQL\FactoryInterface;
use Railt\Contracts\Pipeline\Http\HandlerInterface;
use Railt\Http\Pipeline\Handler\EmptyRequestHandler;
use Railt\Http\Pipeline\Handler\BufferRequestHandler;

/**
 * Class Connection
 */
abstract class Connection implements ConnectionInterface
{
    /**
     * @var int
     */
    private static int $lastId = 0;

    /**
     * @var bool
     */
    protected bool $closed = false;

    /**
     * @var int
     */
    private int $id;

    /**
     * @var array|\Closure[]
     */
    private array $subscribers = [];

    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $app;

    /**
     * @var HttpKernel
     */
    private HttpKernel $kernel;

    /**
     * Connection constructor.
     *
     * @param ContainerInterface $app
     * @param HttpKernel $kernel
     */
    public function __construct(ContainerInterface $app, HttpKernel $kernel)
    {
        $this->app = $app;
        $this->kernel = $kernel;
        $this->id = ++self::$lastId;
    }

    /**
     * @param RequestInterface $request
     * @param \Closure|null $notifier
     * @return \Generator|ResponseInterface[]
     * @throws \Exception
     */
    public function listen(RequestInterface $request, \Closure $notifier = null): \Generator
    {
        yield $response = $this->handle($request);

        $buffer = new BufferRequestHandler($response);

        $notifier ??= fn (...$args) => null;

        while (! $this->closed) {
            try {
                $response = $notifier($this, $request);

                if ($response instanceof ResponseInterface) {
                    yield $this->sendTo($request, $buffer->withResponse($response));
                }
            } catch (\Throwable $error) {
                yield $this->sendTo($request, $buffer->withException($error));
            } finally {
                yield;
            }
        }
    }

    /**
     * @param RequestInterface $request
     * @param HandlerInterface $handler
     * @return ResponseInterface
     */
    protected function sendTo(RequestInterface $request, HandlerInterface $handler): ResponseInterface
    {
        $app = $this->getContainer($request, $handler);

        return $this->kernel->handle($app, $request, $handler);
    }

    /**
     * @param RequestInterface $request
     * @param HandlerInterface $handler
     * @return ContainerInterface
     */
    protected function getContainer(RequestInterface $request, HandlerInterface $handler): ContainerInterface
    {
        $container = new Container($this->app);

        $container->instance(HandlerInterface::class, $handler);
        $container->instance(ConnectionInterface::class, $this);
        $container->instance(RequestInterface::class, $request);

        return $container;
    }

    /**
     * @return HandlerInterface
     */
    protected function getDefaultHandler(): HandlerInterface
    {
        return new EmptyRequestHandler(self::class);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * @param \Closure $handler
     * @return void
     */
    public function onClose(\Closure $handler): void
    {
        $this->subscribers[] = $handler;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * @return void
     */
    public function close(): void
    {
        $this->closed = true;

        foreach ($this->subscribers as $handler) {
            $handler($this);
        }

        $this->dispose();
    }

    /**
     * @return void
     */
    private function dispose(): void
    {
        $this->subscribers = [];
    }
}
