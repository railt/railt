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
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface;

    /**
     * @param string[] ...$middleware
     * @return RouteInterface
     */
    public function middleware(string ...$middleware): RouteInterface;

    /**
     * @param string[] ...$queryTypes
     * @return RouteInterface
     */
    public function type(string ...$queryTypes): RouteInterface;

    /**
     * @param string $route
     * @return bool
     */
    public function match(string $route): bool;


    /**
     * @param array $params
     * @return mixed
     */
    public function call(array $params = []);
}
