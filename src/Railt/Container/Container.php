<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

use Psr\Container\ContainerInterface;

/**
 * Class Container
 * @package Railt\Container
 */
class Container implements ContainerInterface, AllowsInvocations
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @var array|object[]
     */
    private $resolved = [];

    /**
     * @var ParamResolver
     */
    private $paramResolver;

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->paramResolver = new ParamResolver($this);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function get($id)
    {
        $result = $this->items[$id] ?? null;

        switch (true) {
            case $result instanceof \Closure:
                return $this->resolveClosure($result);

            case is_string($result) && class_exists($result):
                return $this->resolveInstance($result);
        }

        return $result;
    }

    /**
     * @param string $class
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function make(string $class)
    {
        if (!$this->has($class)) {
            $this->bind($class, $class);
        }

        return $this->get($class);
    }

    /**
     * @param \Closure $closure
     * @return mixed
     * @throws \ReflectionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function resolveClosure(\Closure $closure)
    {
        $reflection = new \ReflectionFunction($closure);

        $params = iterator_to_array($this->paramResolver->resolve($reflection));

        return $reflection->invoke($params);
    }

    /**
     * @param string $class
     * @return object
     * @throws \ReflectionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function resolveInstance(string $class)
    {
        $args = [];

        if (!array_key_exists($class, $this->resolved)) {
            $reflection = new \ReflectionClass($class);
            $constructor = $this->getMethod('__construct', $reflection);

            if ($constructor !== null) {
                $args = iterator_to_array($this->paramResolver->resolve($constructor));
            }

            $this->resolved[$class] = $reflection->newInstanceArgs($args);
        }

        return $this->resolved[$class];
    }

    /**
     * @param string $name
     * @param \ReflectionClass $ctx
     * @return null|\ReflectionMethod
     */
    private function getMethod(string $name, \ReflectionClass $ctx): ?\ReflectionMethod
    {
        if ($ctx->hasMethod($name)) {
            return $ctx->getMethod($name);
        }

        if ($ctx->getParentClass()) {
            return $this->getMethod($name, $ctx->getParentClass());
        }

        return null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param string $key
     * @param object|string|\Closure $target
     * @return $this
     */
    public function bind(string $key, $target)
    {
        $this->items[$key] = $target;

        return $this;
    }

    /**
     * @param callable|\ReflectionFunctionAbstract|string $action
     * @param array $params
     * @param string $namespace
     * @return \Traversable
     * @throws \InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function call($action, array $params = [], string $namespace = '')
    {
        if (is_string($action)) {
            $stringable = new StringableAction($this, $namespace);
            $action = $stringable->toCallable($action);
        }

        if (is_callable($action)) {
            $action = \Closure::fromCallable($action);
        }

        $args = $this->paramResolver->resolve(new \ReflectionFunction($action), new Parameters($params));

        return call_user_func_array($action, iterator_to_array($args));
    }
}
