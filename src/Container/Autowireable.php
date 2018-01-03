<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

/**
 * Interface Autowireable
 */
interface Autowireable
{
    /**
     * @param callable $callable
     * @param array $params
     * @return mixed
     */
    public function call(callable $callable, array $params = []);

    /**
     * @param string $class
     * @param array $params
     * @return mixed|object
     */
    public function make(string $class, array $params = []);
}
