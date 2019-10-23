<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Container;

use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;

/**
 * Interface Autowireable
 */
interface Autowireable
{
    /**
     * @param callable|\Closure|mixed $callable
     * @param array $params
     * @return mixed
     * @throws ContainerInvocationException
     */
    public function call($callable, array $params = []);

    /**
     * @param string $class
     * @param array $params
     * @return mixed|object
     * @throws ContainerResolutionException
     */
    public function make(string $class, array $params = []);
}
