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
     * @var PSRContainer|null
     */
    private $parent;

    /**
     * @var ParamResolver
     */
    private $params;

    /**
     * @var SignatureResolver
     */
    private $signature;

    /**
     * Container constructor.
     *
     * @param PSRContainer|null $parent
     */
    public function __construct(PSRContainer $parent = null)
    {
        $this->parent = $parent;
        $this->instance(ContainerInterface::class, $this);

        $this->params = new ParamResolver($this);
        $this->signature = new SignatureResolver($this);
    }

    /**
     * @param string $locator
     * @param mixed|object $instance
     * @return Registrable|$this
     */
    public function instance(string $locator, $instance): Registrable
    {
        $this->resolved[$locator] = $instance;

        return $this;
    }

    /**
     * @param string $class
     * @param \Closure $resolver
     * @return Registrable|$this
     */
    public function register(string $class, \Closure $resolver): Registrable
    {
        $this->registered[$class] = $resolver;

        if (isset($this->resolved[$class])) {
            unset($this->resolved[$class]);
        }

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
     * @param string $locator
     * @param array $params
     * @return mixed|object
     * @throws ContainerResolutionException
     */
    public function make(string $locator, array $params = [])
    {
        if ($this->has($locator)) {
            return $this->get($locator);
        }

        if (! \class_exists($locator)) {
            $error = \sprintf('Class %s not found or cannot be instantiated', $locator);
            throw new ContainerResolutionException($error);
        }

        try {
            return new $locator(...$this->params->fromConstructor($locator, $params));
        } catch (\ReflectionException $e) {
            throw new ContainerResolutionException($e->getMessage(), $e->getCode(), $e);
        }
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
        return isset($this->resolved[$service])
            || isset($this->registered[$service])
            || \array_key_exists($service, $this->resolved)
            || \array_key_exists($service, $this->registered);
    }

    /**
     * @param string $id
     * @return mixed|object
     * @throws ContainerResolutionException
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
     * @return mixed|object
     * @throws ContainerResolutionException
     */
    protected function resolve(string $id)
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
     * @param string $id
     * @return bool
     */
    protected function isResolved(string $id): bool
    {
        return isset($this->resolved[$id]) || \array_key_exists($id, $this->resolved);
    }

    /**
     * @param callable|\Closure|mixed $callable
     * @param array $params
     * @return mixed
     * @throws ContainerResolutionException
     */
    public function call($callable, array $params = [])
    {
        try {
            $callable = $this->signature->resolve($callable, $params);
            $resolved = $this->params->fromClosure($callable, $params);
        } catch (\ReflectionException | \InvalidArgumentException $e) {
            throw new ContainerResolutionException($e->getMessage(), $e->getCode(), $e);
        }

        return \call_user_func_array($callable, $resolved);
    }
}
