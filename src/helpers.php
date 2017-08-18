<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Hoa\Compiler\Llk\TreeNode;
use Hoa\Compiler\Visitor\Dump;
use Illuminate\Support\Debug\Dumper;

if (!function_exists('dump')) {
    /**
     * @param TreeNode|mixed $value
     * @return string
     */
    function dump($value): string
    {
        if ($value instanceof TreeNode) {
            $result = (string)(new Dump())->visit($value);

            $result = str_replace('>  ', '    ', $result);
            $result = preg_replace('/^\s{4}/ium', '', $result);
        } else {
            ob_start();
            (new Dumper())->dump($value);
            $result = ob_get_contents();
            ob_end_clean();
        }

        return $result;
    }
}

if (!function_exists('to_array')) {
    /**
     * @param iterable $items
     * @return array
     */
    function to_array(iterable $items): array
    {
        return $items instanceof \Traversable ? iterator_to_array($items) : $items;
    }
}

if (!function_exists('filter')) {
    /**
     * @param iterable $items
     * @param callable $filter
     * @return Traversable
     */
    function filter(iterable $items, callable $filter): \Traversable
    {
        foreach ($items as $key => $value) {
            if (call_user_func($filter, $value, $key)) {
                yield $key => $value;
            }
        }
    }
}

if (!function_exists('map')) {
    /**
     * @param iterable $items
     * @param callable $filter
     * @return Traversable
     */
    function map(iterable $items, callable $filter): \Traversable
    {
        foreach ($items as $key => $value) {
            $result = call_user_func($filter, $value, $key);

            if ($result instanceof \Traversable) {
                yield from $result;
            } elseif ($result) {
                yield $result;
            }
        }
    }
}
