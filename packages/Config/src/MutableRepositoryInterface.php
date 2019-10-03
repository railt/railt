<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Config;

/**
 * Interface MutableRepositoryInterface
 */
interface MutableRepositoryInterface extends RepositoryInterface
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
     * @param RepositoryInterface $repository
     * @return void
     */
    public function merge(RepositoryInterface $repository): void;

    /**
     * @param \Closure $then
     * @return void
     */
    public function onUpdate(\Closure $then): void;
}
