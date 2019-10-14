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
     * @var array|string[]|MiddlewareInterface[]
     */
    private array $registered = [];

    /**
     * @var array|MiddlewareInterface[]
     */
    private ?array $booted = null;

    /**
     * @param MiddlewareInterface|string ...$middleware
     * @return PipelineInterface|$this
     */
    public function through(...$middleware): PipelineInterface
    {
        foreach ($middleware as $item) {
            \assert(\is_subclass_of($item, MiddlewareInterface::class));

            $this->booted = null;
            $this->registered[] = $item;
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
        $this->boot($app);

        foreach ($this->booted as $item) {
            $handler = new Next($item, $handler);
        }

        return $handler;
    }

    /**
     * @param ContainerInterface $container
     * @return void
     */
    private function boot(ContainerInterface $container): void
    {
        if ($this->booted === null) {
            $this->booted = [];

            foreach ($this->registered as $middleware) {
                $this->booted[] = $middleware instanceof MiddlewareInterface
                    ? $middleware
                    : $container->make($middleware);
            }
        }
    }
}
