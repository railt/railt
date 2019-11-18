<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Container\SignatureResolver;

use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;

/**
 * Class InvocableObjectFetcher
 */
class InvocableObjectFetcher extends AbstractFetcher
{
    /**
     * @param callable|object $signature
     * @return bool
     */
    public function match($signature): bool
    {
        return \is_object($signature);
    }

    /**
     * @param callable|mixed $signature
     * @return \Closure
     * @throws ContainerInvocationException
     */
    public function fetchAction($signature): \Closure
    {
        \assert(\is_object($signature));

        try {
            return \Closure::fromCallable($signature);
        } catch (ContainerResolutionException $e) {
            throw new ContainerInvocationException($e->getMessage(), $e->getCode(), $e);
        } catch (\TypeError $e) {
            $error = 'Given class %s should provide method __invoke to use as callable type';
            throw new ContainerInvocationException(\sprintf($error, $this->fetchClass($signature)));
        }
    }

    /**
     * @param object $signature
     * @return string|null
     */
    public function fetchClass($signature): ?string
    {
        \assert(\is_object($signature));

        return \get_class($signature);
    }
}
