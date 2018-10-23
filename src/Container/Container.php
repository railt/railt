<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

use Psr\Container\ContainerInterface as PSRContainer;
use Railt\Container\Exception\ContainerResolutionException;

/**
 * Class Container
 */
class Container implements ContainerInterface
{
    /**
     * @var array|\Closure[]
     */
    private $registered = [];

    /**
     * @var array|object[]
     */
    private $resolved = [];

    /**
     * @var array|string
     */
    private $aliases = [];

    /**
     * @var null|PSRContainer
     */
    private $parent;

    /**
     * @var ParamResolver
     */
    private $resolver;

    /**
     * Container constructor.
     * @param PSRContainer|null $parent
     */
    public function __construct(PSRContainer $parent = null)
    {
        $this->parent = $parent;
        $this->instance(ContainerInterface::class, $this);

        $this->resolver = new ParamResolver($this);
    }

    /**
     * @param string $class
     * @param \Closure $resolver
     * @return Registrable|$this
     */
    public function register(string $class, \Closure $resolver): Registrable
    {
        $this->registered[$class] = $resolver;

        return $this;
    }

    /**
     * @param string $locator
     * @param object $instance
     * @return Registrable|$this
     */
    public function instance(string $locator, $instance): Registrable
    {
        $this->resolved[$locator] = $instance;

        return $this;
    }

    /**
     * @param string $original
     * @param string ...$aliases
     * @return Registrable|$this
     */
    public function alias(string $original, string ...$aliases): Registrable
    {
        foreach ($aliases as $alias) {
            $this->aliases[$alias] = $original;
        }

        return $this;
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
        $locator = $this->getLocator($id);

        if ($this->isRegistered($locator)) {
            return $this->resolve($locator);
        }

        if ($this->parent && $this->parent->has($locator)) {
            return $this->parent->get($locator);
        }

        $error = \sprintf('"%s" entry is not registered', $id);

        if ($id !== $locator) {
            $error = \sprintf('"%s" entry defined as "%s" is not registered', $id, $locator);
        }

        throw new ContainerResolutionException($error);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        $locator = $this->getLocator($id);

        if ($this->isRegistered($locator)) {
            return true;
        }

        return $this->parent && $this->parent->has($locator);
    }

    /**
     * @param string $id
     * @return string
     */
    private function getLocator(string $id): string
    {
        return $this->aliases[$id] ?? $id;
    }

    /**
     * @param string $service
     * @return bool
     */
    private function isRegistered(string $service): bool
    {
        return \array_key_exists($service, $this->resolved) ||
            \array_key_exists($service, $this->registered);
    }

    /**
     * @param string $id
     * @return bool
     */
    private function isResolved(string $id): bool
    {
        return \array_key_exists($id, $this->resolved);
    }

    /**
     * @param string $id
     * @return mixed|object
     * @throws ContainerResolutionException
     * @throws \ReflectionException
     */
    private function resolve(string $id)
    {
        $locator = $this->getLocator($id);

        if (! $this->isRegistered($locator)) {
            throw new ContainerResolutionException('Unresolvable dependency ' . $id);
        }

        if (! $this->isResolved($locator)) {
            $this->resolved[$locator] = $this->call($this->registered[$locator]);
        }

        return $this->resolved[$locator];
    }

    /**
     * @param callable $callable
     * @param array $params
     * @return mixed
     * @throws \ReflectionException
     */
    public function call(callable $callable, array $params = [])
    {
        if (! ($callable instanceof \Closure)) {
            $callable = \Closure::fromCallable($callable);
        }

        $resolved = $this->resolver->fromClosure($callable, $params);

        return \call_user_func_array($callable, $resolved);
    }

    /**
     * @param string $class
     * @param array $params
     * @return mixed|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function make(string $class, array $params = [])
    {
        if ($this->has($class)) {
            return $this->get($class);
        }

        return new $class(...$this->resolver->fromConstructor($class, $params));
    }
}
