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
 * Interface FetcherInterface
 */
interface FetcherInterface
{
    /**
     * @param callable|mixed $signature
     * @return bool
     */
    public function match($signature): bool;

    /**
     * @param callable|mixed $signature
     * @return string|null
     */
    public function fetchClass($signature): ?string;

    /**
     * @param callable|mixed $signature
     * @return \Closure
     */
    public function fetchAction($signature): \Closure;
}
