<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

use Railt\Container\Exceptions\ParameterResolutionException;

/**
 * Class ParamResolver
 */
class ParamResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ParamResolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Closure $action
     * @param array $additional
     * @return array
     * @throws \Railt\Container\Exceptions\ParameterResolutionException
     * @throws \ReflectionException
     */
    public function fromClosure(\Closure $action, array $additional = []): array
    {
        return $this->resolve(new \ReflectionFunction($action), $additional);
    }

    /**
     * @param callable $action
     * @param array $additional
     * @return array
     * @throws \Railt\Container\Exceptions\ParameterResolutionException
     * @throws \ReflectionException
     */
    public function fromCallable(callable $action, array $additional = []): array
    {
        return $this->fromClosure(\Closure::fromCallable($action), $additional);
    }

    /**
     * @param string $class
     * @param array $additional
     * @return array
     * @throws \Railt\Container\Exceptions\ParameterResolutionException
     * @throws \ReflectionException
     */
    public function fromConstructor(string $class, array $additional = []): array
    {
        if (\method_exists($class, '__construct')) {
            return $this->resolve((new \ReflectionClass($class))->getMethod('__construct'), $additional);
        }

        $parent = \get_parent_class($class);

        if ($parent === false) {
            return [];
        }

        return $this->fromConstructor($parent, $additional);
    }

    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param array $additional
     * @return array
     * @throws \Railt\Container\Exceptions\ParameterResolutionException
     */
    public function resolve(\ReflectionFunctionAbstract $reflection, array $additional = []): array
    {
        $result = [];

        foreach ($reflection->getParameters() as $parameter) {
            $result[] = $this->resolveParameter($parameter, $additional);
        }

        return $result;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @param array $additional
     * @return mixed
     * @throws \Railt\Container\Exceptions\ParameterResolutionException
     */
    private function resolveParameter(\ReflectionParameter $parameter, array $additional = [])
    {
        if ($parameter->hasType()) {
            $hint           = $parameter->getType()->getName();
            $hasDefinedType = $this->has($hint, $additional);

            if ($hasDefinedType) {
                return $this->get($hint, $additional);
            }
        }

        if (\array_key_exists($parameter->getName(), $additional)) {
            return $additional[$parameter->getName()];
        }

        if ($parameter->isOptional()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->isVariadic()) {
            return;
        }

        $error = \vsprintf('Can not resolve parameter %s([#%s => %s])', [
            $parameter->getDeclaringFunction()->getName(),
            $parameter->getPosition(),
            $parameter->getName(),
        ]);

        throw new ParameterResolutionException($error);
    }

    /**
     * @param string $service
     * @param array $additional
     * @return bool
     */
    private function has(string $service, array $additional = []): bool
    {
        return \array_key_exists($service, $additional) || $this->container->has($service);
    }

    /**
     * @param string $service
     * @param array $additional
     * @return mixed|object
     */
    private function get(string $service, array $additional = [])
    {
        return $additional[$service] ?? $this->container->make($service, $additional);
    }
}
