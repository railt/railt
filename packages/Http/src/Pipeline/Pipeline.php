<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline;

use Railt\Http\Pipeline\Handler\Next;
use Railt\Container\ContainerInterface;
use Railt\Http\Pipeline\Handler\HandlerInterface;

/**
 * Class Pipeline
 */
abstract class Pipeline implements PipelineInterface
{
    /**
     * @var array|MiddlewareInterface[]
     */
    protected array $middleware = [];

    /**
     * @param MiddlewareInterface|string ...$middleware
     * @return PipelineInterface|$this
     */
    public function through(...$middleware): PipelineInterface
    {
        foreach ($middleware as $item) {
            \assert(\is_subclass_of($item, MiddlewareInterface::class));

            $this->middleware[] = $item;
        }

        return $this;
    }

    /**
     * @param ContainerInterface $app
     * @param HandlerInterface $handler
     * @return HandlerInterface
     */
    protected function handler(ContainerInterface $app, HandlerInterface $handler): HandlerInterface
    {
        foreach ($this->getMiddleware($app) as $item) {
            $handler = new Next($item, $handler);
        }

        return $handler;
    }

    /**
     * @param ContainerInterface $container
     * @return \Traversable|MiddlewareInterface[]
     */
    private function getMiddleware(ContainerInterface $container): \Traversable
    {
        foreach ($this->middleware as $middleware) {
            yield \is_string($middleware) ? $container->make($middleware) : $middleware;
        }
    }
}
