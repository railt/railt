<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container\Container;

/**
 * Interface Aliased
 */
interface Aliased
{
    /**
     * @param string $locator
     * @param string ...$aliases
     * @return Aliased|$this
     */
    public function alias(string $locator, string ...$aliases): self;
}
