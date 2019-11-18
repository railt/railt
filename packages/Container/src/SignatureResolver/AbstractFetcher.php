<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container\SignatureResolver;

use Railt\Container\SignatureResolver;
use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerInvocationException;

/**
 * Class AbstractFetcher
 */
abstract class AbstractFetcher implements FetcherInterface
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * AbstractFetcher constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param callable|mixed $signature
     * @return string|null
     */
    public function fetchClass($signature): ?string
    {
        return null;
    }

    /**
     * @param callable|mixed $signature
     * @return \Closure
     * @throws ContainerInvocationException
     */
    public function fetchAction($signature): \Closure
    {
        if (! \is_callable($signature)) {
            $error = \sprintf('%s is not callable', SignatureResolver::signatureToString($signature));
            throw new ContainerInvocationException($error);
        }

        return \Closure::fromCallable($signature);
    }
}
