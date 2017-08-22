<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Support\HashMap;

/**
 * Class Router
 * @package Railt\Routing
 */
class Router implements \IteratorAggregate
{
    private const MEMOISED_ROUTE_KEY = 0;
    private const MEMOISED_RESPONDER_KEY = 1;

    /**
     * @var HashMap|Respondent[]|HashMap<Route,Respondent>
     */
    private $routes;

    /**
     * @var array[]
     */
    private $compiled = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->routes = new HashMap();
    }

    /**
     * @param $prefix
     * @param \Closure $body
     * @return Route
     * @throws \InvalidArgumentException
     */
    public function group($prefix, \Closure $body): Route
    {
        return tap($this->makeRoute($prefix), function (Route $parent) use ($body) {
            /** @var Router $router */
            $router = tap(new Router(), $body);

            /**
             * @var Route $route
             */
            foreach ($router as $route => $then) {
                $this->add($route->into($parent), $then);
            }
        });
    }

    /**
     * @param Route|string $route
     * @return Route
     */
    private function makeRoute($route): Route
    {
        return is_string($route) ? new Route($route) : $route;
    }

    /**
     * @param Route|string $route
     * @param callable|\Closure|string|Respondent $then
     * @return Route
     * @throws \InvalidArgumentException
     */
    public function when($route, $then): Route
    {
        return tap($this->makeRoute($route), function (Route $route) use ($then) {
            $this->routes[$route] = Respondent::new($then);
        });
    }

    /**
     * @return iterable
     */
    public function all(): iterable
    {
        yield from $this->getIterator();
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        yield from $this->routes->getIterator();
    }

    /**
     * @param string $uri
     * @return bool
     * @throws \Railt\Exceptions\IndeterminateBehaviorException
     * @throws \Railt\Exceptions\CompilerException
     */
    public function has(string $uri): bool
    {
        return $this->memoiseRouteFor($uri);
    }

    /**
     * @param string $uri
     * @return bool
     * @throws \Railt\Exceptions\IndeterminateBehaviorException
     * @throws \Railt\Exceptions\CompilerException
     */
    private function memoiseRouteFor(string $uri): bool
    {
        if (!array_key_exists($uri, $this->compiled)) {
            $this->compiled[$uri] = [];

            /**
             * @var Route $route
             * @var Respondent $respondent
             */
            foreach ($this->routes as $route => $respondent) {
                if ($route->match($uri)) {
                    $this->compiled[$uri][] = [
                        self::MEMOISED_ROUTE_KEY     => $route,
                        self::MEMOISED_RESPONDER_KEY => $respondent,
                    ];
                }
            }

            return count($this->compiled[$uri]) > 0;
        }

        return true;
    }

    /**
     * @param string $uri
     * @return iterable|Respondent[]
     * @throws \Railt\Exceptions\IndeterminateBehaviorException
     * @throws \Railt\Exceptions\CompilerException
     */
    public function resolve(string $uri): iterable
    {
        if ($this->memoiseRouteFor($uri)) {
            foreach ($this->compiled[$uri] as $found) {
                yield $found[self::MEMOISED_RESPONDER_KEY];
            }
        }
    }

    /**
     * @param string $uri
     * @return iterable|Route[]
     * @throws \Railt\Exceptions\IndeterminateBehaviorException
     * @throws \Railt\Exceptions\CompilerException
     */
    public function find(string $uri): iterable
    {
        if ($this->memoiseRouteFor($uri)) {
            foreach ($this->compiled[$uri] as $found) {
                yield $found[self::MEMOISED_ROUTE_KEY];
            }
        }
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return to_array(map($this->routes, function (Respondent $value, Route $key) {
            return $key;
        }));
    }
}
