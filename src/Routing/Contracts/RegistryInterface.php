<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Contracts;

/**
 * Interface RegistryInterface
 */
interface RegistryInterface
{
    /**
     * @param string $key
     * @param $data
     * @return mixed|iterable
     */
    public function set(string $key, $data);

    /**
     * @param string $key
     * @param null $default
     * @return mixed|iterable
     */
    public function get(string $key, $default = null);

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;
}
