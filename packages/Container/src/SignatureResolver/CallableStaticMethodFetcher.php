<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Container\SignatureResolver;

/**
 * Class CallableStaticMethodFetcher
 */
class CallableStaticMethodFetcher extends AbstractFetcher
{
    /**
     * @var string
     */
    private const CALLABLE_METHOD_DELIMITER = '::';

    /**
     * @param callable|mixed $signature
     * @return bool
     */
    public function match($signature): bool
    {
        return \is_string($signature) && \substr_count($signature, self::CALLABLE_METHOD_DELIMITER) === 1;
    }

    /**
     * @param callable|mixed $signature
     * @return string|null
     */
    public function fetchClass($signature): ?string
    {
        \assert(\is_string($signature));

        $chunks = \explode(self::CALLABLE_METHOD_DELIMITER, $signature);

        return \reset($chunks);
    }
}
