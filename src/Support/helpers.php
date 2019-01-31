<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Support\Debug\Debuggable;
use Railt\Support\Iter;
use Railt\Support\TypeDumper;

if (! \function_exists('\\iterable_map')) {
    /**
     * @param callable $map
     * @param iterable ...$iterables
     * @return \Generator
     */
    function iterable_map(callable $map, iterable ...$iterables): \Generator
    {
        yield from Iter::map($map, ...$iterables);
    }
}


if (! \function_exists('\\iterable_filter')) {
    /**
     * @param callable $filter
     * @param iterable ...$iterables
     * @return \Generator
     */
    function iterable_filter(callable $filter, iterable ...$iterables): \Generator
    {
        yield from Iter::filter($filter, ...$iterables);
    }
}


if (! \function_exists('\\dump_type')) {
    /**
     * @param mixed $value
     * @return string
     */
    function dump_type($value): string
    {
        return TypeDumper::dump($value);
    }
}


if (! \function_exists('\\is_debug')) {
    /**
     * @param Debuggable $debuggable
     * @return bool
     */
    function is_debug(Debuggable $debuggable): bool
    {
        return $debuggable->isDebug();
    }
}
