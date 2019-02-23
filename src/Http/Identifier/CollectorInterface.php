<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Identifier;

/**
 * Interface CollectorInterface
 */
interface CollectorInterface
{
    /**
     * @param string $class
     * @return int
     */
    public static function next(string $class): int;

    /**
     * @param string $class
     * @return int
     */
    public static function current(string $class): int;
}
