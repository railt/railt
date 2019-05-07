<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Container\SignatureResolver;

/**
 * Class CallableFunctionFetcher
 */
class CallableFunctionFetcher extends AbstractFetcher
{
    /**
     * @param callable $signature
     * @return bool
     */
    public function match($signature): bool
    {
        return \is_callable($signature) && \is_string($signature) && \function_exists($signature);
    }
}
