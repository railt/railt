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
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Container\Exception\ParameterResolutionException;

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
     * @return SignatureResolver
     */
    public function getSignatureResolver(): SignatureResolver
    {
        return $this->signature;
    }

    /**
     * @return ParamResolver
     */
    public function getParamResolver(): ParamResolver
    {
        return $this->params;
    }

    /**
     * @param string $id
     * @param \Closure $then
     * @return Registrable|$this
     */
    public function register(string $id, \Closure $then): Registrable
    {
        $this->registered[$id] = $then;

        if (isset($this->resolved[$id])) {
            unset($this->resolved[$id]);
        }

        return $this;
    }

    /**
     * @param string $class
     * @param \Closure $then
     * @return Container
     */
    public function registerIfNotRegistered(string $class, \Closure $then): self
    {
        if (! $this->has($class)) {
            $this->register($class, $then);
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
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    public function make(string $locator, array $params = [])
    {
        if ($this->has($locator)) {
            return $this->getWithParameters($locator, $params);
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
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     */
    public function get($id)
    {
        return $this->getWithParameters($id);
    }

    /**
     * @param mixed $id
     * @param array $additional
     * @return mixed|object
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     */
    private function getWithParameters($id, array $additional = [])
    {
        $locator = $this->getLocator($id);

        if ($this->isRegistered($locator)) {
            return $this->resolve($locator, $additional);
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
     * @param array $additional
     * @return mixed|object
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     */
    protected function resolve(string $id, array $additional = [])
    {
        $locator = $this->getLocator($id);

        if (! $this->isRegistered($locator)) {
            throw new ContainerResolutionException('Unresolvable dependency ' . $id);
        }

        if (! $this->isResolved($locator)) {
            $this->resolved[$locator] = $this->call($this->registered[$locator], $additional);
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
     * @throws ContainerInvocationException
     */
    public function call($callable, array $params = [])
    {
        $action = $this->signature->fetchAction($callable);

        try {
            $resolvedParameters = $this->params->fromClosure($action, $params);
        } catch (\ReflectionException $e) {
            throw new ContainerInvocationException($e->getMessage(), $e->getCode(), $e);
        }

        return $action(...$resolvedParameters);
    }
}
