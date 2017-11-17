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
 * Class Parameters
 */
class Parameters implements ContainerInterface
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var Container
     */
    private $container;

    /**
     * Parameters constructor.
     * @param array $parameters
     * @throws \BadMethodCallException
     * @throws \ReflectionException
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
        $this->container  = new Container();

        $this->with($parameters);
    }

    /**
     * @param array $parameters
     * @return Parameters
     * @throws \ReflectionException
     * @throws \BadMethodCallException
     */
    public function with(array $parameters = []): self
    {
        foreach ($parameters as $key => $value) {
            if (\is_int($key)) {
                $this->registerAnonymousParameter($value);
            } else {
                $this->registerNamedParameter($key, $value);
            }
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @throws \BadMethodCallException
     * @throws \ReflectionException
     */
    private function registerAnonymousParameter($value)
    {
        switch (true) {
            case \is_object($value):
                return $this->registerNamedParameter(\get_class($value), $value);
            case \is_string($value):
                return $this->registerNamedParameter($value, $value);
        }

        $error = 'Can not register dynamic parameter %s without key.';
        throw new \BadMethodCallException(\sprintf($error, \gettype($value)));
    }

    /**
     * @param string $key
     * @param mixed $value
     * @throws \ReflectionException
     */
    private function registerNamedParameter($key, $value): void
    {
        $this->container->singleton($key, $value);

        switch (true) {
            case \is_object($value):
                foreach ($this->getObjectAliases($value) as $alias) {
                    if (! $this->container->has($alias)) {
                        $this->container->singleton($alias, $value);
                    }
                }
                break;
            case \is_string($value) && \class_exists($value):
                foreach ($this->getClassAliases($value) as $alias) {
                    if (! $this->container->has($alias)) {
                        $this->container->singleton($alias, $value);
                    }
                }
                break;
        }
    }

    /**
     * @param object $object
     * @return \Traversable|string[]
     * @throws \ReflectionException
     */
    private function getObjectAliases($object): \Traversable
    {
        yield from $this->getClassAliases(\get_class($object));
    }

    /**
     * @param string $class
     * @return \Traversable|string[]
     * @throws \ReflectionException
     */
    private function getClassAliases(string $class): \Traversable
    {
        $reflection = new \ReflectionClass($class);

        yield $reflection->getName();

        foreach ($reflection->getInterfaces() as $interface) {
            yield $interface->getName();
        }
    }

    /**
     * @param callable|string $callable
     * @param array $params
     * @return mixed|void
     * @throws \BadMethodCallException
     */
    public function call(callable $callable, array $params = [])
    {
        throw new \BadMethodCallException('Method not allowed');
    }

    /**
     * @param string $id
     * @return mixed
     * @throws Exceptions\ContainerResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return $this->container->has($id);
    }
}
