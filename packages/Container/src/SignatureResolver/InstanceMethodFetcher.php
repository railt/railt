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
 * Class InstanceMethodFetcher
 */
class InstanceMethodFetcher extends AbstractFetcher
{
    /**
     * @var string
     */
    private const INSTANCE_METHOD_DELIMITER = '@';

    /**
     * @param callable|mixed $signature
     * @return bool
     */
    public function match($signature): bool
    {
        return \is_string($signature)
            && \substr_count($signature, self::INSTANCE_METHOD_DELIMITER) === 1;
    }

    /**
     * @param string $signature
     * @return string|null
     */
    public function fetchClass($signature): ?string
    {
        \assert(\is_string($signature));

        return $this->split($signature)[0];
    }

    /**
     * @param string $signature
     * @return array|string[]
     */
    private function split(string $signature): array
    {
        return \explode(self::INSTANCE_METHOD_DELIMITER, $signature);
    }

    /**
     * @param string $signature
     * @return \Closure
     * @throws ContainerInvocationException
     */
    public function fetchAction($signature): \Closure
    {
        \assert(\is_string($signature));

        [$class, $method] = $this->split($signature);

        try {
            return \Closure::fromCallable([$this->container->make($class), $method]);
        } catch (ContainerResolutionException $e) {
            throw new ContainerInvocationException($e->getMessage(), $e->getCode(), $e);
        } catch (\TypeError $e) {
            $error = 'Given object of %s does not contain a method %s';
            throw new ContainerInvocationException(\sprintf($error, $this->fetchClass($signature), $method));
        }
    }
}
