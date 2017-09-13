<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Container\ContainerInterface;
use Railt\Routing\Contracts\RouteInterface;
use Railt\Routing\Contracts\RouterInterface;

/**
 * Class Router
 * @package Railt\Routing
 */
class Router implements RouterInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $namespaces = [];

    /**
     * @var array|RouteInterface[]
     */
    private $routes = [];

    /**
     * @var array
     */
    private $cached = [];

    /**
     * Router constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $route
     * @param callable|string $then
     * @return RouteInterface
     */
    public function any(string $route, $then): RouteInterface
    {
        return $this->add($route, $then);
    }

    /**
     * @param string $route
     * @param callable|string $then
     * @return Route
     */
    private function add(string $route, $then): Route
    {
        $this->reset();

        return $this->routes[] = new Route($this, $route, $then);
    }

    /**
     * Reset cache
     * @return void
     */
    private function reset(): void
    {
        $this->cached = [];
    }

    /**
     * @param string $route
     * @param callable|string $then
     * @return RouteInterface
     */
    public function query(string $route, $then): RouteInterface
    {
        return $this->add($route, $then)->type(Route::REQUEST_TYPE_QUERY);
    }

    /**
     * @param string $route
     * @param callable|string $then
     * @return RouteInterface
     */
    public function mutation(string $route, $then): RouteInterface
    {
        return $this->add($route, $then)->type(Route::REQUEST_TYPE_MUTATION);
    }

    /**
     * @param string $route
     * @param callable|string $then
     * @return RouteInterface
     */
    public function subscription(string $route, $then): RouteInterface
    {
        return $this->add($route, $then)->type(Route::REQUEST_TYPE_SUBSCRIPTION);
    }

    /**
     * @param string $route
     * @return iterable|RouteInterface[]
     */
    public function get(string $route): iterable
    {
        if (! array_key_exists($route, $this->cached)) {
            $this->compileRouteCacheIfNotCompiled($route);
        }

        return $this->cached[$route];
    }

    /**
     * @param string $routePath
     */
    private function compileRouteCacheIfNotCompiled(string $routePath): void
    {
        $this->cached[$routePath] = [];

        foreach ($this->routes as $route) {
            if ($route->match($routePath)) {
                $this->cached[$routePath][] = $route;
            }
        }
    }

    /**
     * @param string $route
     * @return bool
     */
    public function has(string $route): bool
    {
        if (! array_key_exists($route, $this->cached)) {
            $this->compileRouteCacheIfNotCompiled($route);
        }

        return count($this->cached[$route]) > 0;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return array
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }
}
