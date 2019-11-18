<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

use Railt\Container\SignatureResolver\ClosureFetcher;
use Railt\Container\SignatureResolver\FetcherInterface;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\SignatureResolver\CallableArrayFetcher;
use Railt\Container\SignatureResolver\InstanceMethodFetcher;
use Railt\Container\SignatureResolver\InvocableClassFetcher;
use Railt\Container\SignatureResolver\InvocableObjectFetcher;
use Railt\Container\SignatureResolver\CallableFunctionFetcher;
use Railt\Container\SignatureResolver\CallableStaticMethodFetcher;

/**
 * Class SignatureResolver
 */
class SignatureResolver
{
    /**
     * @var array|string[]|FetcherInterface[]
     */
    private const DEFAULT_FETCHER_CLASSES = [
        ClosureFetcher::class,
        CallableArrayFetcher::class,
        CallableFunctionFetcher::class,
        CallableStaticMethodFetcher::class,
        InstanceMethodFetcher::class,
        InvocableClassFetcher::class,
        InvocableObjectFetcher::class,
    ];

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @var array|FetcherInterface[]
     */
    private array $fetchers = [];

    /**
     * SignatureResolver constructor.
     *
     * @param ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ?? new Container();

        $this->bootDefaultFetchers();
    }

    /**
     * @return void
     */
    private function bootDefaultFetchers(): void
    {
        foreach (self::DEFAULT_FETCHER_CLASSES as $class) {
            $this->register($class);
        }
    }

    /**
     * @param string|FetcherInterface $resolver
     */
    public function register(string $resolver): void
    {
        $this->fetchers[] = new $resolver($this->container);
    }

    /**
     * @param callable|mixed $signature
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function fetchClass($signature): ?string
    {
        if ($fetcher = $this->match($signature)) {
            return $fetcher->fetchClass($signature);
        }

        $error = 'Could not determine callable format of %s';
        throw new \InvalidArgumentException(\sprintf($error, static::signatureToString($signature)));
    }

    /**
     * @param mixed|callable $signature
     * @return FetcherInterface|null
     */
    public function match($signature): ?FetcherInterface
    {
        foreach ($this->fetchers as $fetcher) {
            if ($fetcher->match($signature)) {
                return $fetcher;
            }
        }

        return null;
    }

    /**
     * @param mixed $signature
     * @return string
     */
    public static function signatureToString($signature): string
    {
        $pattern = '%s %s';

        switch (true) {
            case $signature === null:
                return 'null value';

            case \is_object($signature):
                return \sprintf($pattern, 'object', \get_class($signature));

            case \is_string($signature):
                return \sprintf($pattern, 'string', '"' . \addcslashes($signature, '"') . '"');

            case \is_array($signature):
                $items = \array_map([static::class, 'signatureToString'], $signature);

                return '[ ' . \implode(', ', $items) . ' ]';

            default:
                return \sprintf($pattern, \strtolower(\gettype($signature)), $signature);
        }
    }

    /**
     * @param callable|mixed $signature
     * @return \Closure
     * @throws ContainerInvocationException
     */
    public function fetchAction($signature): \Closure
    {
        if ($fetcher = $this->match($signature)) {
            return $fetcher->fetchAction($signature);
        }

        $error = '%s is not allowed for invocations';
        throw new ContainerInvocationException(\sprintf($error, static::signatureToString($signature)));
    }
}
