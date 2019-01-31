<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Support;

/**
 * Class Iter
 */
class Iter
{
    /**
     * The filter method iterates over each value in the iterable passing them
     * to the callback function. If the callback function returns true, the
     * current value from iterable is returned into the result iterator.
     *
     * Iterable keys are preserved.
     *
     * @param callable $filter Callback function to run for each element in each iterable.
     * @param iterable ...$iterables An iterable items to run through the callback function.
     * @return \Generator
     */
    public static function filter(callable $filter, iterable ...$iterables): \Generator
    {
        foreach ($iterables as $iterable) {
            foreach ($iterable as $key => $value) {
                if ($filter($value, $key)) {
                    yield $key => $value;
                }
            }
        }
    }

    /**
     * The map method returns an iterator containing all the elements of
     * $iterable after applying the callback function to each one.
     *
     * The number of parameters that the callback function accepts should
     * match the number of iterable arguments passed to the map method.
     *
     * @param callable $map Callback function to run for each element in each iterable.
     * @param iterable ...$iterables An iterable items to run through the callback function.
     * @return \Generator
     */
    public static function map(callable $map, iterable ...$iterables): \Generator
    {
        foreach ($iterables as $iterable) {
            foreach ($iterable as $key => $value) {
                yield $map($value, $key);
            }
        }
    }

    /**
     * The map method returns an iterator containing all keys of
     * $iterable after applying the callback function to each one.
     *
     * The number of parameters that the callback function accepts should
     * match the number of iterable arguments passed to the map method.
     *
     * @param callable $map Callback function to run for each element in each iterable.
     * @param iterable ...$iterables An iterable items to run through the callback function.
     * @return \Generator
     */
    public static function mapKeys(callable $map, iterable ...$iterables): \Generator
    {
        foreach ($iterables as $iterable) {
            foreach ($iterable as $key => $value) {
                yield $map($key, $value) => $value;
            }
        }
    }
}
