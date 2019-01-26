<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Support\Maybe;

if (! \function_exists('\\class_basename')) {
    /**
     * Get the short class name of the given object or class except namespace.
     *
     * @param string|object $class
     * @return string
     */
    function class_basename($class)
    {
        $class = \is_object($class) ? \get_class($class) : $class;

        return \basename(\str_replace('\\', '/', $class));
    }
}


if (! \function_exists('\\iterable_map')) {
    /**
     * iterable_map() returns an array containing all the elements of
     * $iterable after applying the callback function to each one.
     *
     * The number of parameters that the callback function accepts should
     * match the number of iterable arguments passed to the iterable_map()
     *
     * @param callable $map Callback function to run for each element in each iterable.
     * @param iterable ...$iterables An iterable items to run through the callback function.
     * @return \Generator
     */
    function iterable_map(callable $map, iterable ...$iterables): \Generator
    {
        foreach ($iterables as $iterable) {
            foreach ($iterable as $key => $value) {
                yield $map($value, $key);
            }
        }
    }
}


if (! \function_exists('\\iterable_filter')) {
    /**
     * iterable_filter() iterates over each value in the iterable passing them
     * to the callback function. If the callback function returns true, the
     * current value from iterable is returned into the result iterable.
     *
     * Iterable keys are preserved.
     *
     * @param callable $filter Callback function to run for each element in each iterable.
     * @param iterable ...$iterables An iterable items to run through the callback function.
     * @return \Generator
     */
    function iterable_filter(callable $filter, iterable ...$iterables): \Generator
    {
        foreach ($iterables as $iterable) {
            foreach ($iterable as $key => $value) {
                if ($filter($value, $key)) {
                    yield $key => $value;
                }
            }
        }
    }
}


if (! \function_exists('\\maybe')) {
    /**
     * @param mixed $value
     * @return Maybe
     */
    function maybe($value): Maybe
    {
        return new Maybe($value);
    }
}
