<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Events;

/**
 * Interface Dispatchable
 * @package Railt\Events
 */
interface DispatcherInterface
{
    /**
     * @param string $name
     * @param \Closure $then
     * @return DispatcherInterface
     */
    public function listen(string $name, \Closure $then): DispatcherInterface;

    /**
     * @param string $name
     * @param array ...$payload
     * @return array
     */
    public function dispatch(string $name, ...$payload): array;
}
