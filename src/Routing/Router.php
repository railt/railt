<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Routing;

use Serafim\Railgun\Runtime\RequestInterface;

/**
 * Class Router
 * @package Serafim\Railgun\Routing
 */
class Router
{
    /**
     * @var array|Route[]
     */
    private $routes = [];

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param string $pattern
     * @return Route
     */
    public function when(string $pattern): Route
    {
        return $this->routes[] = Route::new($pattern);
    }

    /**
     * @param string $parameter
     * @param string $regex
     * @return Router
     */
    public function where(string $parameter, string $regex): Router
    {
        $this->parameters[$parameter] = $regex;

        return $this;
    }

    /**
     * @param RequestInterface $request
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     */
    public function dispatch(RequestInterface $request)
    {
        $this->compile();

        foreach ($this->groups($request) as $route) {
            dd($route);
        }
    }

    /**
     * @param RequestInterface $request
     * @return \Traversable|Route[]
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     */
    private function groups(RequestInterface $request): \Traversable
    {
        foreach ($this->routes as $route) {
            if ($route->startsWith($request->getPath())) {
                yield $route;
            }
        }
    }

    /**
     * @return void
     */
    private function compile(): void
    {
        foreach ($this->parameters as $name => $regex) {
            /** @var Route $route */
            foreach ($this->routes as $route) {
                $route->where($name, $regex);
            }
        }
    }
}
