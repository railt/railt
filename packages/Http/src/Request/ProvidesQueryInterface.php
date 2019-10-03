<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

/**
 * Interface QueryInterface
 */
interface ProvidesQueryInterface
{
    /**
     * @return string
     */
    public function getQuery(): string;

    /**
     * @return bool
     */
    public function isEmpty(): bool;
}
