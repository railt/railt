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
 * Interface Registrable
 */
interface Registrable
{
    /**
     * @param string $locator
     * @param \Closure $resolver
     * @return void
     */
    public function register(string $locator, \Closure $resolver): void;

    /**
     * @param string $locator
     * @param object $instance
     * @return void
     */
    public function instance(string $locator, $instance): void;

    /**
     * @param string $locator
     * @param string $alias
     * @return void
     */
    public function alias(string $locator, string $alias): void;
}
