<?php
/**
 * This file is part of templates package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

/**
 * Interface RegistrableInterface
 */
interface RegistrableInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @return RegistrableInterface
     */
    public function factory(string $key, $value): RegistrableInterface;

    /**
     * @param string $key
     * @param mixed $value
     * @return RegistrableInterface
     */
    public function singleton(string $key, $value): RegistrableInterface;
}
