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
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
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
     * @var array|Route[]|\SplStack[]
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
     * @param TypeDefinition $type
     * @param string $field
     * @return Route
     */
    public function route(TypeDefinition $type, string $field = null): Route
    {
        $route = new Route($this->container, $type);

        if ($field !== null) {
            $route->when($field);
        }

        return $this->add($route);
    }

    /**
     * @param TypeDefinition $type
     * @return string
     */
    private function key(TypeDefinition $type): string
    {
        return $type->getUniqueId();
    }

    /**
     * @param TypeDefinition $type
     * @return bool
     */
    public function has(TypeDefinition $type): bool
    {
        return \array_key_exists($this->key($type), $this->routes);
    }

    /**
     * @param TypeDefinition $type
     * @return iterable|Route[]
     */
    public function get(TypeDefinition $type): iterable
    {
        return $this->routes[$this->key($type)] ?? [];
    }

    /**
     * @param Route $route
     * @return Route
     */
    public function add(Route $route): Route
    {
        $index = $this->key($route->getTypeDefinition());

        if (! \array_key_exists($index, $this->routes)) {
            $this->routes[$index] = new \SplQueue();
        }

        $this->routes[$index]->push($route);

        return $route;
    }
}
