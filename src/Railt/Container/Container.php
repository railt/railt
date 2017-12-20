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
     * @var null|ContainerInterface
     */
    private $parent;

    /**
     * Container constructor.
     * @param ContainerInterface|null $parent
     */
    public function __construct(ContainerInterface $parent = null)
    {
        $this->parent = $parent;

        $this->resolved[ContainerInterface::class] = $this;
    }

    /**
     * @param string $method
     * @param array $params
     * @param \Closure $otherwise
     * @return mixed
     */
    private function proxy(string $method, array $params = [], \Closure $otherwise)
    {
        if ($this->parent !== null) {
            return \call_user_func_array([$this->parent, $method], $params);
        }

        return $otherwise();
    }

    /**
     * @param string $class
     * @param \Closure $resolver
     * @return void
     */
    public function register(string $class, \Closure $resolver): void
    {
        $this->registered[$class] = $resolver;
    }

    /**
     * @param string $locator
     * @param object $instance
     * @return void
     */
    public function instance(string $locator, $instance): void
    {
        $this->resolved[$locator] = $instance;

        $this->registered[$locator] = function () use ($instance) {
            return $instance;
        };
    }

    /**
     * @param string $original
     * @param string $alias
     * @return void
     */
    public function alias(string $original, string $alias): void
    {
        $this->aliases[$alias] = $original;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->proxy('get', [$id], function () use ($id) {
            return $this->resolve($id);
        });
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return $this->proxy('has', [$id], function () use ($id): bool {
            return $this->getLocator($id) !== null;
        });
    }

    /**
     * @param string $id
     * @return null|string
     */
    private function getLocator(string $id): ?string
    {
        $service = $this->aliases[$id] ?? $id;

        if (\array_key_exists($service, $this->registered)) {
            return $service;
        }

        return null;
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
     */
    private function resolve(string $id)
    {
        $locator = $this->getLocator($id);

        if ($locator === null) {
            throw new \LogicException('Unresolvable dependency ' . $id);
        }

        if (! $this->isResolved($locator)) {
            $this->resolved[$locator] = $this->call($this->registered[$locator]);
        }

        return $this->resolved[$locator];
    }

    public function call(callable $callable, array $params = []): void
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function make(string $class, array $params = []): void
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
