<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Routing\Contracts\RouteInterface;
use Railt\Routing\Contracts\RouterInterface;

/**
 * Class Router.
 */
class Router implements RouterInterface
{
    /**
     * @var array|RouteInterface[]
     */
    protected $routes = [];

    /**
     * @var array
     */
    private $storage = [];

    /**
     * @param string $route
     * @param callable|string $then
     * @return RouteInterface
     */
    public function query(string $route, $then): RouteInterface
    {
        return $this->any($route, $then)->method(Route::METHOD_QUERY);
    }

    /**
     * @param string $route
     * @param callable|string $then
     * @return RouteInterface
     */
    public function any(string $route, $then): RouteInterface
    {
        return $this->routes[] = (new Route($route))->then($then);
    }

    /**
     * @param string $route
     * @param callable|string $then
     * @return RouteInterface
     */
    public function mutation(string $route, $then): RouteInterface
    {
        return $this->any($route, $then)->method(Route::METHOD_MUTATION);
    }

    /**
     * @param string $route
     * @param callable|string $then
     * @return RouteInterface
     */
    public function subscription(string $route, $then): RouteInterface
    {
        return $this->any($route, $then)->method(Route::METHOD_SUBSCRIPTION);
    }

    /**
     * @param string $route
     * @return iterable|RouteInterface[]
     */
    public function find(string $route): iterable
    {
        if (! \array_key_exists($route, $this->storage)) {
            $this->storage[$route] = \iterator_to_array($this->matchRoutes($route));
        }

        return $this->storage[$route];
    }

    /**
     * @param string $route
     * @return \Traversable|RouteInterface[]
     */
    private function matchRoutes(string $route): \Traversable
    {
        foreach ($this->routes as $exists) {
            if ($exists->match($route)) {
                yield $exists;
            }
        }
    }

    /**
     * @param string $route
     * @return bool
     */
    public function has(string $route): bool
    {
        return \count($this->find($route)) > 0;
    }
}
