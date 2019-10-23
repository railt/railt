<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Observer;

/**
 * Interface ObservableInterface
 */
interface ObservableInterface
{
    /**
     * @param \Closure $observer
     * @param bool $once
     * @return void
     */
    public function subscribe(\Closure $observer, bool $once = false): void;

    /**
     * @param \Closure $observer
     * @return bool
     */
    public function unsubscribe(\Closure $observer): bool;
}
