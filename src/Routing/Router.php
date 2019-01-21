<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Http\InputInterface;
use Railt\Http\RequestInterface;

/**
 * Class Router
 */
class Router implements RouterInterface, \Countable
{
    /**
     * @var array|RouteInterface[]
     */
    private $routes = [];

    /**
     * @param RequestInterface $request
     * @param InputInterface $input
     * @return RouteInterface[]|iterable
     */
    public function resolve(RequestInterface $request, InputInterface $input): iterable
    {
        foreach ($this->routes as $route) {
            if ($route->match($request, $input)) {
                yield $route;
            }
        }
    }

    /**
     * @param RouteInterface $route
     * @return RouterInterface
     */
    public function add(RouteInterface $route): RouterInterface
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * @param callable|mixed $action
     * @return RouteInterface
     */
    public function create($action): RouteInterface
    {
        $this->add($route = new Route($action));

        return $route;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->routes);
    }
}
