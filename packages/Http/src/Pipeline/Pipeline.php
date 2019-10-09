<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline;

use Railt\Container\Container;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Http\ConnectionInterface;
use Railt\Container\ContainerInterface;
use Railt\Http\Pipeline\Handler\RequestEmptyHandler;

/**
 * Class Pipeline
 */
class Pipeline implements PipelineInterface
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $app;

    /**
     * @var array|RequestMiddlewareInterface[]
     */
    private array $middleware = [];

    /**
     * Pipeline constructor.
     *
     * @param ContainerInterface $app
     */
    public function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    /**
     * @param RequestMiddlewareInterface|string $middleware
     * @return PipelineInterface|$this
     */
    public function through($middleware): PipelineInterface
    {
        \assert(\is_subclass_of($middleware, RequestMiddlewareInterface::class));

        $this->middleware[] = $middleware;

        return $this;
    }

    /**
     * @param ConnectionInterface $connection
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function send(ConnectionInterface $connection, RequestInterface $request): ResponseInterface
    {
        return $this->sendTo($connection, $request, $this->getTarget());
    }

    /**
     * @return RequestHandlerInterface
     */
    private function getTarget(): RequestHandlerInterface
    {
        return new RequestEmptyHandler(static::class);
    }

    /**
     * @param ConnectionInterface $connection
     * @param RequestInterface $request
     * @return ContainerInterface
     */
    private function connectionContainer(ConnectionInterface $connection, RequestInterface $request): ContainerInterface
    {
        $container = new Container($this->app);
        $container->instance(ConnectionInterface::class, $connection);
        $container->instance(RequestInterface::class, $request);

        return $container;
    }

    /**
     * @param ConnectionInterface $connection
     * @param RequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function sendTo(ConnectionInterface $connection, RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $container = $this->connectionContainer($connection, $request);

        foreach ($this->getMiddleware($container) as $item) {
            $handler = new Next($item, $handler);
        }

        return $handler->handle($request);
    }

    /**
     * @param ContainerInterface $container
     * @return \Traversable|RequestMiddlewareInterface[]
     */
    private function getMiddleware(ContainerInterface $container): \Traversable
    {
        foreach ($this->middleware as $middleware) {
            yield \is_string($middleware) ? $container->make($middleware) : $middleware;
        }
    }
}
