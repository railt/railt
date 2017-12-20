<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\ServiceProviders;

use Railt\Container\ContainerInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

/**
 * Class BaseServiceProvider
 */
abstract class BaseServiceProvider implements ServiceProvider
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * BaseServiceProvider constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Can be overriden
     *
     * @param RequestInterface $request
     * @param \Closure $then
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, \Closure $then): ResponseInterface
    {
        return $then($request);
    }

    /**
     * @param callable $callable
     * @param array $params
     * @return mixed
     */
    public function call(callable $callable, array $params = [])
    {
        return $this->container->call($callable, $params);
    }

    /**
     * @param string $class
     * @param array $params
     * @return mixed|object
     */
    public function make(string $class, array $params = [])
    {
        return $this->container->make($class, $params);
    }

    /**
     * @param string $class
     * @param \Closure $resolver
     * @return void
     */
    public function register(string $class, \Closure $resolver): void
    {
        $this->container->register($class, $resolver);
    }

    /**
     * @param string $locator
     * @param object $instance
     * @return void
     */
    public function instance(string $locator, $instance): void
    {
        $this->container->instance($locator, $instance);
    }

    /**
     * @param string $original
     * @param string $alias
     * @return void
     */
    public function alias(string $original, string $alias): void
    {
        $this->container->alias($original, $alias);
    }
}
