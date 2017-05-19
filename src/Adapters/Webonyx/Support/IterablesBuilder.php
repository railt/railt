<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Support;

/**
 * Trait IterablesBuilder
 * @package Serafim\Railgun\Adapters\Webonyx\Support
 */
trait IterablesBuilder
{
    /**
     * @param iterable $items
     * @param string $applyFn
     * @return array
     */
    public function makeIterable(iterable $items, string $applyFn = 'build'): array
    {
        $result = [];

        foreach ($items as $name => $item) {
            $result[$name] = $this->{$applyFn}($item, is_string($name) ? $name : null);
        }

        return $result;
    }

    /**
     * @param iterable $items
     * @param string $applyFn
     * @return array
     */
    public function makeIterableValues(iterable $items, string $applyFn = 'build'): array
    {
        $result = [];

        foreach ($items as $item) {
            $result[] = $this->{$applyFn}($item);
        }

        return $result;
    }
}
