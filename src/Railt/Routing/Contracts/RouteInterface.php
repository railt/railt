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
 * Interface RouteInterface
 */
interface RouteInterface
{
    /**
     * @param string[] ...$middleware
     * @return RouteInterface
     */
    public function middleware(string ...$middleware): RouteInterface;

    /**
     * @param string[] ...$methods
     * @return RouteInterface
     */
    public function method(string ...$methods): RouteInterface;

    /**
     * @param string $route
     * @return bool
     */
    public function match(string $route): bool;

    /**
     * @param string|callable|\Closure $action
     * @return RouteInterface
     */
    public function then($action): RouteInterface;
}
