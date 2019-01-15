<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

use Railt\Container\Exception\ParameterResolutionException;

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
     * @param callable $action
     * @param array $additional
     * @return array
     * @throws \ReflectionException
     */
    public function fromCallable(callable $action, array $additional = []): array
    {
        return $this->fromClosure(\Closure::fromCallable($action), $additional);
    }

    /**
     * @param \Closure $action
     * @param array $additional
     * @return array
     * @throws \ReflectionException
     */
    public function fromClosure(\Closure $action, array $additional = []): array
    {
        return $this->resolve(new \ReflectionFunction($action), $additional);
    }

    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param array $additional
     * @return array
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
     */
    private function resolveParameter(\ReflectionParameter $parameter, array $additional)
    {
        return $this->resolveByTypeHint($parameter, $additional);
    }

    /**
     * @param \ReflectionParameter $parameter
     * @param array $additional
     * @return mixed|object
     */
    private function resolveByTypeHint(\ReflectionParameter $parameter, array $additional)
    {
        if ($parameter->hasType()) {
            /** @noinspection NullPointerExceptionInspection */
            $name = $parameter->getType()->getName();

            return $this->resolveParameterByName($name, $additional, function () use ($parameter, $additional) {
                return $this->resolveByName($parameter, $additional);
            });
        }

        return $this->resolveByName($parameter, $additional);
    }

    /**
     * @param string $name
     * @param array $additional
     * @param \Closure $otherwise
     * @return mixed
     */
    private function resolveParameterByName(string $name, array $additional, \Closure $otherwise)
    {
        /** @noinspection NotOptimalIfConditionsInspection */
        if (isset($additional[$name]) || \array_key_exists($name, $additional)) {
            return $additional[$name];
        }

        if ($this->container->has($name)) {
            return $this->container->get($name);
        }

        return $otherwise($name, $additional);
    }

    /**
     * @param \ReflectionParameter $parameter
     * @param array $additional
     * @return mixed|object
     */
    private function resolveByName(\ReflectionParameter $parameter, array $additional)
    {
        $name = '$' . $parameter->getName();

        return $this->resolveParameterByName($name, $additional, function () use ($parameter) {
            return $this->resolveDefault($parameter);
        });
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return mixed|null
     * @throws ParameterResolutionException
     */
    private function resolveDefault(\ReflectionParameter $parameter)
    {
        if ($parameter->isOptional()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->isVariadic()) {
            return null;
        }

        throw $this->parameterError($parameter);
    }

    /**
     * @param \ReflectionParameter $param
     * @return ParameterResolutionException
     */
    private function parameterError(\ReflectionParameter $param): ParameterResolutionException
    {
        $type = $param->hasType() ? $param->getType() : 'mixed';
        $name = $param->getName();
        $position = $param->getPosition();
        $function = $param->getDeclaringFunction()->getName();

        $error = \vsprintf('Cannot resolve parameter #%d "%s $%s" defined in %s(...)', [
            $position,
            $type,
            $name,
            $function,
        ]);

        return ParameterResolutionException::fromReflectionFunction($error, $param->getDeclaringFunction());
    }

    /**
     * @param string $class
     * @param array $additional
     * @return array
     * @throws \Railt\Container\Exception\ParameterResolutionException
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
}
