<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Pipeline;

use Railt\Container\ContainerInterface;
use Railt\Dumper\TypeDumper;
use Railt\Pipeline\Exception\MiddlewareException;

/**
 * Class Pipeline
 */
class Pipeline implements PipelineInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var \Closure|null
     */
    private $handler;

    /**
     * @var array
     */
    private $middleware = [];

    /**
     * Pipeline constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Closure|null $then
     * @return PipelineInterface|$this
     */
    public function then(?\Closure $then): PipelineInterface
    {
        $this->handler = $then;

        return $this;
    }

    /**
     * @param \Closure|string|object $middleware
     * @return PipelineInterface|$this
     * @throws \Railt\Container\Exception\ContainerResolutionException
     */
    public function through($middleware): PipelineInterface
    {
        $isInstantiable = \is_string($middleware) && (\class_exists($middleware) || $this->container->has($middleware));

        if ($isInstantiable) {
            $middleware = $this->container->make($middleware);
        }

        $this->middleware[] = $middleware;

        return $this;
    }

    /**
     * @return \Traversable|\Closure[]
     * @throws MiddlewareException
     */
    private function each(): \Traversable
    {
        foreach (\array_reverse($this->middleware) as $middleware) {
            switch (true) {
                case \is_callable($middleware):
                    yield $this->fromCallable($middleware);
                    break;

                case \is_object($middleware):
                    yield $this->fromObject($middleware);
                    break;

                default:
                    $error = 'Middleware should be a valid callable type, but %s given';
                    $error = \sprintf($error, TypeDumper::render($middleware));

                    throw new MiddlewareException($error);
            }
        }
    }

    /**
     * @param object $middleware
     * @return \Closure
     * @throws MiddlewareException
     */
    private function fromObject($middleware): \Closure
    {
        if (\method_exists($middleware, 'handle')) {
            return \Closure::fromCallable([$middleware, 'handle']);
        }

        if (\is_callable($middleware)) {
            return \Closure::fromCallable($middleware);
        }

        $error = \sprintf('Middleware object should provide %s::handle() method', \get_class($middleware));
        throw new MiddlewareException($error);
    }

    /**
     * @param callable $middleware
     * @return \Closure
     */
    private function fromCallable(callable $middleware): \Closure
    {
        return \Closure::fromCallable($middleware);
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws MiddlewareException
     */
    public function send($value)
    {
        $terminal = $this->handler ?? function () {
            return null;
        };

        foreach ($this->each() as $handler) {
            $terminal = function ($value) use ($handler, $terminal) {
                return $handler($value, $terminal);
            };
        }

        return $terminal($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws MiddlewareException
     */
    public function __invoke($value)
    {
        return $this->send($value);
    }
}
