<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions;


/**
 * Class Factory
 */
abstract class Factory
{
    /**
     * @var array
     */
    protected static $items = [];

    /**
     * @param mixed $value
     */
    protected static function add($value): void
    {
        static::$items[] = $value;
    }

    /**
     * @param \Closure $filter
     * @return mixed|null
     */
    protected static function first(\Closure $filter)
    {
        foreach (static::$items as $item) {
            if ($filter($item)) {
                return $item;
            }
        }

        return null;
    }
}
