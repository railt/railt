<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Subscribers;

/**
 * Class Attribute
 */
final class Attribute
{
    /**
     * @param mixed $item
     * @param string $field
     * @param \Closure $otherwise
     * @return mixed
     */
    public static function read($item, string $field, \Closure $otherwise = null)
    {
        if (self::inArray($item, $field)) {
            return $item[$field];
        }

        if ($otherwise === null) {
            return null;
        }

        return $otherwise($item);
    }

    /**
     * @param mixed $item
     * @param string $field
     * @return bool
     */
    private static function inArray($item, string $field): bool
    {
        return \is_array($item) && (isset($item[$field]) || \array_key_exists($field, $item));
    }
}
