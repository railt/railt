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
 * Interface Dispatchable
 */
interface Dispatchable
{
    /**
     * Selects the most appropriate method based on the
     * passed arguments. One of the variants of realization
     * of multiplication (double) dispatching.
     *
     * @param iterable $methods
     * @param mixed ...$params
     * @return mixed
     */
    public function dispatch(iterable $methods, ...$params);
}
