<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Dumper\Resolver;

/**
 * Interface SelfDisplayed
 */
interface SelfDisplayed
{
    /**
     * @param string $type
     * @param string $value
     * @return string
     */
    public function render(string $type, string $value): string;
}
