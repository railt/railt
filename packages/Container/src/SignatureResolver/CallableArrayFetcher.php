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
 * Class CallableArrayFetcher
 */
class CallableArrayFetcher extends AbstractFetcher
{
    /**
     * @param callable|mixed $signature
     * @return bool
     */
    public function match($signature): bool
    {
        return \is_array($signature) && \count($signature) === 2;
    }

    /**
     * @param array|callable $signature
     * @return string|null
     */
    public function fetchClass($signature): ?string
    {
        \assert(\is_array($signature));

        $context = $signature[0];

        return \is_object($context) ? \get_class($context) : $context;
    }
}
