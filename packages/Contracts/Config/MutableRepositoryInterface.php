<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Config;

use Railt\Contracts\Observer\ObservableInterface;

/**
 * Interface MutableRepositoryInterface
 */
interface MutableRepositoryInterface extends ObservableInterface, RepositoryInterface
{
    /**
     * Set a given configuration value.
     *
     * @param string $key
     * @param mixed|null $value
     * @return void
     */
    public function set(string $key, $value = null): void;

    /**
     * Adds the data of the passed repository to the existing one.
     *
     * @param iterable $items
     * @return void
     */
    public function merge(iterable $items): void;
}
