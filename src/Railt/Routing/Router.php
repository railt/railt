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
use Railt\Routing\Contracts\RouterInterface;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

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

        return $this->routes[$this->key($type)] = $route;
    }

    /**
     * @param TypeDefinition $type
     * @return string
     */
    private function key(TypeDefinition $type): string
    {
        return (string)$type;
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
     * @return null|Route
     */
    public function get(TypeDefinition $type): ?Route
    {
        return $this->routes[$this->key($type)] ?? null;
    }
}
