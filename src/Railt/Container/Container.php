<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Railt\Container\Definitions\FactoryDefinition;
use Railt\Container\Definitions\SingletonDefinition;
use Railt\Container\Exceptions\ContainerResolutionException;

/**
 * Class Container
 * @package Railt\Container
 */
class Container implements ContainerInterface
{
    /**
     * @var ContainerInterface
     */
    private $parent;

    /**
     * @var array
     */
    private $definitions = [];

    /**
     * @var array|\ReflectionFunction[]
     */
    private $closures = [];

    /**
     * @var ParamResolver
     */
    private $paramResolver;

    /**
     * Container constructor.
     * @param ContainerInterface|null $parent
     */
    public function __construct(ContainerInterface $parent = null)
    {
        $this->parent = $parent;
        $this->paramResolver = new ParamResolver($this);
    }

    /**
     * @param string $id
     * @return bool
     */
    private function hasParent($id): bool
    {
        if ($this->parent === null) {
            return false;
        }

        return $this->parent->has($id);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws ContainerResolutionException
     * @throws NotFoundExceptionInterface
     */
    private function getParent(string $id)
    {
        $error = 'Trying to resolve unregistered entity "%s"';

        if ($this->parent === null) {
            throw new ContainerResolutionException(sprintf($error, $id));
        }

        try {
            return $this->parent->get($id);
        } catch (\Throwable $e) {
            throw new ContainerResolutionException(sprintf($error, $id), $e->getCode(), $e);
        }
    }

    /**
     * @param callable $callable
     * @param array $params
     * @return mixed
     * @throws \BadMethodCallException
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Railt\Container\Exceptions\ContainerResolutionException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \ReflectionException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function call(callable $callable, array $params = [])
    {
        $reflection = $this->getReflectionFunction($callable);

        $args = $this->paramResolver->resolve($reflection, $params);

        return call_user_func_array($callable, iterator_to_array($args));
    }

    /**
     * @param callable $callable
     * @return \ReflectionFunction
     * @throws \ReflectionException
     */
    private function getReflectionFunction(callable $callable): \ReflectionFunction
    {
        switch (true) {
            case is_string($callable):
                if (!array_key_exists($callable, $this->closures)) {
                    $this->closures[$callable] = new \ReflectionFunction($callable);
                }

                return $this->closures[$callable];
            case $callable instanceof \Closure:
                $key = spl_object_hash($callable);
                if (!array_key_exists($key, $this->closures)) {
                    $this->closures[$key] = new \ReflectionFunction($callable);
                }

                return $this->closures[$key];
        }

        return new \ReflectionFunction(\Closure::fromCallable($callable));
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws ContainerResolutionException
     * @throws NotFoundExceptionInterface
     */
    public function get($id)
    {
        if (array_key_exists($id, $this->definitions)) {
            return $this->definitions[$id]->resolve();
        }

        if ($this->hasParent($id)) {
            return $this->getParent($id);
        }

        if (is_string($id) && class_exists($id)) {
            $this->factory($id, $id);

            return $this->get($id);
        }

        return $this->getParent($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return array_key_exists($id, $this->definitions) || $this->hasParent($id);
    }

    /**
     * @param string $key
     * @param \Closure|string|object $value
     * @return Container|RegistrableInterface
     */
    public function factory(string $key, $value): RegistrableInterface
    {
        $this->definitions[$key] = new FactoryDefinition($this, $value);

        return $this;
    }

    /**
     * @param string $key
     * @param \Closure|string|object $value
     * @return Container|RegistrableInterface
     */
    public function singleton(string $key, $value): RegistrableInterface
    {
        $this->definitions[$key] = new SingletonDefinition($this, $value);

        return $this;
    }
}
