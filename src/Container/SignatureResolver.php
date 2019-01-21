<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

/**
 * Class SignatureResolver
 */
class SignatureResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * SignatureResolver constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $signature
     * @param array $params
     * @return \Closure
     * @throws \InvalidArgumentException
     */
    public function resolve($signature, array $params): \Closure
    {
        switch (true) {
            case static::isClosure($signature):
                return $this->fromClosure($signature);

            case static::isCallable($signature):
                return $this->fromCallable($signature);

            case static::isInvocable($signature):
                return $this->fromInvocable($signature, $params);

            case static::isSignature($signature):
                return $this->fromSignature($signature, $params);

            default:
                throw new \InvalidArgumentException('Could not determine callable format');
        }
    }

    /**
     * @param \Closure|mixed $signature
     * @return bool
     */
    public static function isClosure($signature): bool
    {
        return $signature instanceof \Closure;
    }

    /**
     * @param \Closure $signature
     * @return \Closure
     */
    public function fromClosure(\Closure $signature): \Closure
    {
        return $signature;
    }

    /**
     * @param callable|mixed $signature
     * @return bool
     */
    public static function isCallable($signature): bool
    {
        return \is_callable($signature);
    }

    /**
     * @param callable $callable
     * @return \Closure
     */
    public function fromCallable(callable $callable): \Closure
    {
        return \Closure::fromCallable($callable);
    }

    /**
     * @param callable|mixed $signature
     * @return bool
     */
    public static function isInvocable($signature): bool
    {
        return \is_string($signature) && \class_exists($signature);
    }

    /**
     * @param string $signature
     * @param array $params
     * @return \Closure
     */
    public function fromInvocable(string $signature, array $params): \Closure
    {
        return function() use ($signature, $params) {
            $instance = $this->container->make($signature, $params);

            return $this->container->call([$instance, '__invoke'], $params);
        };
    }

    /**
     * @param callable|mixed $signature
     * @return bool
     */
    public static function isSignature($signature): bool
    {
        return \is_string($signature) && \strpos($signature, '@') !== false;
    }

    /**
     * @param string $signature
     * @param array $params
     * @return \Closure
     * @throws \InvalidArgumentException
     */
    public function fromSignature(string $signature, array $params): \Closure
    {
        if (\substr_count($signature, '@') !== 1) {
            throw new \InvalidArgumentException('Bad signature function format');
        }

        [$class, $method] = \explode('@', $signature);

        return function() use ($class, $method, $params) {
            $instance = $this->container->make($class, $params);

            return $this->container->call([$instance, $method], $params);
        };
    }
}
