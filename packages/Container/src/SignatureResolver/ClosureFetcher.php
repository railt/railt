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
 * Class ClosureFetcher
 */
class ClosureFetcher extends AbstractFetcher
{
    /**
     * @param callable|mixed $signature
     * @return bool
     */
    public function match($signature): bool
    {
        return $signature instanceof \Closure;
    }

    /**
     * @param \Closure $signature
     * @return \Closure
     */
    public function fetchAction($signature): \Closure
    {
        \assert($signature instanceof \Closure);

        return $signature;
    }
}
