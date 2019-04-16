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
 * Providing the ability to get an offset in bytes from the source text.
 */
interface ProvidesOffset
{
    /**
     * Returns an offset value in bytes.
     *
     * @return int
     */
    public function getOffset(): int;
}
