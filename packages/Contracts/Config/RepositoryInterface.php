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
 * Interface RepositoryInterface
 */
interface RepositoryInterface extends \IteratorAggregate
{
    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Determine if the given configuration value exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public function all(): array;
}
