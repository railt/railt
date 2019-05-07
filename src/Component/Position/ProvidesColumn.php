<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Position;

/**
 * Providing the ability to get column of code from source text.
 */
interface ProvidesColumn
{
    /**
     * Returns a column from source code.
     *
     * @return int
     */
    public function getColumn(): int;
}
