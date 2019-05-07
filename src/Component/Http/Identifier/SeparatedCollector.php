<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Identifier;

/**
 * Class SeparatedCollector
 */
class SeparatedCollector implements CollectorInterface
{
    /**
     * @var array|string<int>
     */
    private static $identifiers = [];

    /**
     * @param string $class
     * @return int
     */
    public static function next(string $class): int
    {
        if (! isset(self::$identifiers[$class])) {
            self::$identifiers[$class] = 0;
        }

        return ++self::$identifiers[$class];
    }

    /**
     * @param string $class
     * @return int
     */
    public static function current(string $class): int
    {
        return self::$identifiers[$class] ?? 0;
    }
}
