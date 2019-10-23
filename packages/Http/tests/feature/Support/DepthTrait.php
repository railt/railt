<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Feature\Support;

/**
 * Trait DepthTrait
 */
trait DepthTrait
{
    /**
     * @param string $key
     * @param array|object|string $payload
     * @return mixed
     */
    protected function depth(string $key, $payload)
    {
        $chunks = \array_filter(\preg_split('/\W/u', $key));

        foreach ($chunks as $chunk) {
            if (\is_array($payload)) {
                $payload = $payload[$chunk];
            } elseif (\is_object($payload)) {
                $payload = $payload->$chunk;
            } else {
                return null;
            }
        }

        return $payload;
    }
}
