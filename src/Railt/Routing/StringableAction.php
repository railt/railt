<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Container\ContainerInterface;
use Railt\Container\RegistrableInterface;

/**
 * Class StringableAction
 */
class StringableAction
{
    /**
     * Allowed chars split chars
     */
    private const ACTION_SPLIT_CHARS = ['@', '::', '#'];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * StringableAction constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $action
     * @param array $namespaces
     * @return array
     * @throws \InvalidArgumentException
     */
    private function parse(string $action, array $namespaces): array
    {
        [$class, $action] = $this->stringToArray($action);

        $class = $this->verifyActionClass($namespaces, $class);

        $this->verifyActionMethod($class, $action);

        return [$class, $action];
    }

    /**
     * @param string $action
     * @param array $namespaces
     * @return \Closure
     * @throws \ReflectionException
     * @throws \InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function toCallable(string $action, array $namespaces = []): \Closure
    {
        [$class, $action] = $this->parse($action, $namespaces);

        if ($this->isStaticCallee($class, $action)) {
            return \Closure::fromCallable([$class, $action]);
        }

        $instance = $this->createInstance($class);

        return \Closure::fromCallable([$instance, $action]);
    }

    /**
     * @param string $class
     * @param string $action
     * @return bool
     * @throws \ReflectionException
     */
    private function isStaticCallee(string $class, string $action): bool
    {
        $reflection = new \ReflectionClass($class);

        $method = $reflection->getMethod($action);

        return $method->isStatic();
    }

    /**
     * @param string $class
     * @return object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function createInstance($class)
    {
        $this->registerIfNotRegistered($class);

        return $this->container->get($class);
    }

    /**
     * @param string $class
     */
    private function registerIfNotRegistered($class): void
    {
        $allowsRegister = $this->container instanceof RegistrableInterface;

        if ($allowsRegister && !$this->container->has($class)) {
            $this->container->singleton($class, $class);
        }
    }

    /**
     * @param string $action
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function stringToArray(string $action): array
    {
        foreach (self::ACTION_SPLIT_CHARS as $char) {
            if (mb_stripos($action, $char) !== false) {
                return $this->split($action, $char);
            }
        }

        throw new \InvalidArgumentException('Invalid action format "' . $action . '"');
    }


    /**
     * @param string $action
     * @param string $delimiter
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function split(string $action, string $delimiter): array
    {
        $this->verifyActionPartsCount($parts = explode($delimiter, $action));

        return $parts;
    }

    /**
     * @param array $namespaces
     * @param string $class
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function verifyActionClass(array $namespaces = [], string $class): string
    {
        if (class_exists($class)) {
            return $class;
        }

        foreach ($namespaces as $namespace) {
            if (class_exists($namespace . '\\' . $class)) {
                return $namespace . '\\' . $class;
            }
        }

        throw new \InvalidArgumentException('Action class ' . $class . '::class does not exists.');
    }

    /**
     * @param string $class
     * @param string $action
     * @throws \InvalidArgumentException
     */
    protected function verifyActionMethod(string $class, string $action): void
    {
        if (!method_exists($class, $action)) {
            $error = sprintf('Method %s::%s() does not exists', $class, $action);
            throw new \InvalidArgumentException($error);
        }
    }

    /**
     * @param array $parts
     * @throws \InvalidArgumentException
     */
    protected function verifyActionPartsCount(array $parts): void
    {
        $partsCount = count($parts);

        if ($partsCount !== 2) {
            $error = 'Invalid action arguments count, 2 required but %d given.';
            throw new \InvalidArgumentException(sprintf($error, $partsCount));
        }
    }
}
