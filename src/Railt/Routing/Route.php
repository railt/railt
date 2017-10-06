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

/**
 * Class Route
 */
class Route implements RouteInterface
{
    /**
     * GraphQL Query method
     */
    public const METHOD_QUERY = 'query';

    /**
     * GraphQL Mutation method
     */
    public const METHOD_MUTATION = 'mutation';

    /**
     * GraphQL Subscription method
     */
    public const METHOD_SUBSCRIPTION = 'subscription';

    /**
     * GraphQL ANY of already defined methods
     */
    public const METHOD_ANY = [
        self::METHOD_QUERY,
        self::METHOD_MUTATION,
        self::METHOD_SUBSCRIPTION,
    ];

    /**
     * @var string
     */
    private $route;

    /**
     * @var string|null
     */
    private $pattern;

    /**
     * @var array
     */
    private $methods = self::METHOD_ANY;

    /**
     * @var array
     */
    private $middleware = [];

    /**
     * @var string|callable|\Closure
     */
    private $action;

    /**
     * Route constructor.
     * @param string $route
     * @param string $method
     * @internal param callable|\Closure|string $action
     */
    public function __construct(string $route, string $method = null)
    {
        $this->route = $route;
        $this->method(...($method === null ? self::METHOD_ANY : [$method]));
    }

    /**
     * @param callable|\Closure|string $action
     * @return RouteInterface
     */
    public function then($action): RouteInterface
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @param string[] ...$middleware
     * @return RouteInterface
     */
    public function middleware(string ...$middleware): RouteInterface
    {
        $this->middleware = \array_unique(\array_merge($this->middleware, $middleware));

        return $this;
    }

    /**
     * @param string[] ...$methods
     * @return RouteInterface
     */
    public function method(string ...$methods): RouteInterface
    {
        $this->methods = \array_unique($methods);

        return $this;
    }

    /**
     * @param string $route
     * @return bool
     */
    public function match(string $route): bool
    {
        return (int)\preg_match($this->getPattern(), $route) > 0;
    }

    /**
     * @return string
     */
    private function getPattern(): string
    {
        if ($this->pattern === null) {
            $this->pattern = \sprintf('/^%s$/isu', \preg_quote($this->route, '/'));
        }

        return $this->pattern;
    }
}
