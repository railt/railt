<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Component\Json\Json5;

if (! \function_exists('\\json5_decode')) {
    /**
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     * @return mixed
     * @throws \Railt\Component\Json\Exception\JsonException
     */
    function json5_decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0)
    {
        return Json5::decoder()
            ->setOption(\JSON_OBJECT_AS_ARRAY, $assoc)
            ->withRecursionDepth($depth)
            ->withOptions($options)
            ->decode($json);
    }
}

if (! \function_exists('\\json5_encode')) {
    /**
     * @param mixed $value
     * @param int $options
     * @param int $depth
     * @return string
     * @throws \Railt\Component\Json\Exception\JsonException
     */
    function json5_encode($value, int $options = 0, int $depth = 512): string
    {
        return Json5::encoder()
            ->withRecursionDepth($depth)
            ->withOptions($options)
            ->encode($value);
    }
}
