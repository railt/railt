<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Common;

/**
 * Class Iter
 */
final class Iter
{
    /**
     * @template T
     * @psalm-param iterable<mixed, T> $items
     * @psalm-return array<mixed, T>
     * @psalm-suppress InvalidReturnType
     *
     * @param iterable|array|\Traversable $items
     * @param bool $saveKeys
     * @return array
     */
    public static function toArray(iterable $items, bool $saveKeys = true): array
    {
        if ($items instanceof \Traversable) {
            return \iterator_to_array($items, $saveKeys);
        }

        return $saveKeys ? $items : \array_values($items);
    }

    /**
     * @param iterable $items
     * @param bool $saveKeys
     * @return \Traversable
     */
    public static function toTraversable(iterable $items, bool $saveKeys = true): \Traversable
    {
        foreach ($items as $key => $value) {
            if ($saveKeys) {
                yield $key => $value;
            } else {
                yield $value;
            }
        }
    }

    /**
     * @param iterable $items
     * @return int
     */
    public static function count(iterable $items): int
    {
        if ($items instanceof \Traversable) {
            return \iterator_count($items);
        }

        return \count($items);
    }

    /**
     * @param iterable $items
     * @param callable $each
     * @return \Traversable
     */
    public static function map(iterable $items, callable $each): \Traversable
    {
        foreach ($items as $key => $value) {
            yield $key => $each($value, $key);
        }
    }


    /**
     * @template T
     * @psalm-param iterable<mixed, T> $items
     * @psalm-param \Closure(T): array<string, T> $unpack
     * @psalm-return array<string, T>
     *
     * @param iterable $items
     * @param callable $each
     * @return array
     */
    public static function mapToArray(iterable $items, callable $each): array
    {
        $result = [];

        foreach ($items as $item) {
            $result += $each($item);
        }

        return $result;
    }

    /**
     * @param iterable $items
     * @param callable $each
     * @return \Traversable
     */
    public static function mapWithKeys(iterable $items, callable $each): \Traversable
    {
        foreach ($items as $key => $value) {
            yield from $each($value, $key);
        }
    }

    /**
     * @param iterable $items
     * @param callable $each
     * @return \Traversable
     */
    public static function filter(iterable $items, callable $each): \Traversable
    {
        foreach ($items as $key => $value) {
            if ($result = $each($value, $key)) {
                yield $key => $value;
            }
        }
    }

    /**
     * @param iterable $items
     * @param callable $each
     * @return array
     */
    public static function filterToArray(iterable $items, callable $each): array
    {
        $result = [];

        foreach ($items as $key => $value) {
            if ($result = $each($value, $key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param iterable $items
     * @return iterable
     */
    public static function keys(iterable $items): iterable
    {
        foreach ($items as $key => $value) {
            yield $key;
        }
    }

    /**
     * @param iterable $items
     * @return iterable
     */
    public static function values(iterable $items): iterable
    {
        foreach ($items as $key => $value) {
            yield $value;
        }
    }

    /**
     * @param iterable $items
     * @param mixed|null $default
     * @return mixed
     */
    public static function first(iterable $items, $default = null)
    {
        foreach ($items as $value) {
            return $value;
        }

        return $default;
    }

    /**
     * @param iterable $items
     * @param mixed|null $default
     * @return mixed
     */
    public static function firstKey(iterable $items, $default = null)
    {
        foreach ($items as $key => $value) {
            return $key;
        }

        return $default;
    }
}
