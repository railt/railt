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
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Contracts\RouterInterface;

/**
 * Class Router
 */
class Router implements RouterInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array|Route[]
     */
    private $routes = [];

    /**
     * Router constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param FieldDefinition $type
     * @param string $field
     * @return Route
     */
    public function route(FieldDefinition $type, string $field = null): Route
    {
        $route = new Route($this->container, $type);

        if ($field !== null) {
            $route->when($field);
        }

        return $this->add($route);
    }

    /**
     * @param Route $route
     * @return Route
     */
    public function add(Route $route): Route
    {
        $index = $this->key($route->getField());

        if (! \array_key_exists($index, $this->routes)) {
            $this->routes[$index] = [];
        }

        $this->routes[$index][] = $route;

        return $route;
    }

    /**
     * @param FieldDefinition $type
     * @return string
     */
    private function key(FieldDefinition $type): string
    {
        return $type->getUniqueId();
    }

    /**
     * @param FieldDefinition $type
     * @return bool
     */
    public function has(FieldDefinition $type): bool
    {
        return \array_key_exists($this->key($type), $this->routes);
    }

    /**
     * @param FieldDefinition $type
     * @return iterable|Route[]
     */
    public function get(FieldDefinition $type): iterable
    {
        return $this->routes[$this->key($type)] ?? [];
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        $routes = [];

        foreach ($this->routes as $queue) {
            foreach ((array)$queue as $route) {
                $routes[] = $route;
            }
        }

        return [
            'routes' => $routes,
        ];
    }
}
