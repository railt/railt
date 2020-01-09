<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Origin;

use Railt\Introspection\Exception\OriginException;

/**
 * Class Origin
 */
abstract class Origin implements OriginInterface
{
    /**
     * @param string $json
     * @return array
     */
    protected function decode(string $json): array
    {
        return \json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
    }

    /**
     * @param mixed $data
     * @return string
     */
    protected function encode($data): string
    {
        return \json_encode($data, \JSON_THROW_ON_ERROR);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return string
     * @throws OriginException
     */
    protected function read(string $uri, array $options = []): string
    {
        $context = \stream_context_create($options);

        return (string)$this->wrap(fn() =>
            @\file_get_contents($uri, false, $context)
        );
    }

    /**
     * @param \Closure $expr
     * @return mixed
     * @throws OriginException
     */
    protected function wrap(\Closure $expr)
    {
        \error_clear_last();

        $result = $expr();

        if ($error = \error_get_last()) {
            throw new OriginException($error['message'], (int)($error['type'] ?? 0));
        }

        return $result;
    }
}
