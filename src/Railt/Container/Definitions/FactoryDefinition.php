<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container\Definitions;

use Railt\Container\ContainerInterface;
use Railt\Container\ParamResolver;

/**
 * Class FactoryDefinition
 */
class FactoryDefinition implements DefinitionInterface
{
    /**
     * Class constructor
     */
    private const CONSTRUCTOR_NAME = '__construct';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var mixed
     */
    private $target;

    /**
     * @var \ReflectionClass|null
     */
    private $reflection;

    /**
     * @var bool
     */
    private $resolved = false;

    /**
     * @var array|null
     */
    private $params;

    /**
     * FactoryDefinition constructor.
     * @param ContainerInterface $container
     * @param mixed $target
     */
    public function __construct(ContainerInterface $container, $target)
    {
        $this->container = $container;
        $this->target = $target;
    }

    /**
     * @param string $class
     * @return object
     * @throws \BadMethodCallException
     * @throws \ReflectionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function createInstance(string $class)
    {
        if ($this->reflection === null) {
            $this->reflection = new \ReflectionClass($class);
        }

        if ($this->params === null) {
            $constructor = $this->getConstructor($this->reflection);

            if ($constructor) {
                $resolver = new ParamResolver($this->container);
                $this->params = iterator_to_array($resolver->resolve($constructor));
            } else {
                $this->params = [];
            }
        }

        return $this->reflection->newInstanceArgs($this->params);
    }

    /**
     * @param \ReflectionClass $class
     * @return null|\ReflectionMethod
     */
    private function getConstructor(\ReflectionClass $class): ?\ReflectionMethod
    {
        if ($class->hasMethod(self::CONSTRUCTOR_NAME)) {
            return $class->getMethod(self::CONSTRUCTOR_NAME);
        }

        $parent = $class->getParentClass();

        if ($parent) {
            return $this->getConstructor($parent);
        }

        return null;
    }

    /**
     * @return object
     * @throws \BadMethodCallException
     * @throws \LogicException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function resolve()
    {
        switch (true) {
            case is_callable($this->target):
                return $this->container->call($this->target);

            case is_object($this->target):
                if (!$this->resolved) {
                    return $this->resolved = $this->target;
                }

                $this->target = get_class($this->target);

            case is_string($this->target) && class_exists($this->target):
                return $this->createInstance($this->target);
        }

        return $this->target;
    }
}
