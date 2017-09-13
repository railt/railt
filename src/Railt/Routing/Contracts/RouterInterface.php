<?php
/**
 * This file is part of routing package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Contracts;

/**
 * Interface RouterInterface
 * @package Railt\Routing\Contracts
 */
interface RouterInterface
{
    /**
     * @param string $route
     * @param callable|string $action
     * @return RouteInterface
     */
    public function any(string $route, $action): RouteInterface;

    /**
     * @param string $route
     * @param callable|string $action
     * @return RouteInterface
     */
    public function query(string $route, $action): RouteInterface;

    /**
     * @param string $route
     * @param callable|string $action
     * @return RouteInterface
     */
    public function mutation(string $route, $action): RouteInterface;

    /**
     * @param string $route
     * @param callable|string $action
     * @return RouteInterface
     */
    public function subscription(string $route, $action): RouteInterface;

    /**
     * @param string $route
     * @return iterable|RouteInterface[]
     */
    public function get(string $route): iterable;

    /**
     * @param string $route
     * @return bool
     */
    public function has(string $route): bool;
}
