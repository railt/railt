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
 * Class StringableAction
 * @package Railt\Container
 */
class StringableAction
{
    /**
     * Allowed chars split chars
     */
    private const ACTION_SPLIT_CHARS = ['@', '::', '#'];

    /**
     * @var Container
     */
    private $container;

    /**
     * StringableAction constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $action
     * @param string $namespace
     * @return array
     * @throws \InvalidArgumentException
     */
    private function parse(string $action, string $namespace): array
    {
        [$class, $action] = $this->stringToArray($action);

        $class = $this->verifyActionClass($namespace, $class);

        $this->verifyActionMethod($class, $action);

        return [$class, $action];
    }

    /**
     * @param string $action
     * @param string $namespace
     * @return \Closure
     * @throws \ReflectionException
     * @throws Exceptions\ContainerResolutionException
     * @throws \InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function toCallable(string $action, string $namespace = ''): \Closure
    {
        [$class, $action] = $this->parse($action, $namespace);

        $reflection = new \ReflectionClass($class);

        $method = $reflection->getMethod($action);

        if ($method->isStatic()) {
            return \Closure::fromCallable([$class, $action]);
        }

        if (!$this->container->has($class)) {
            $this->container->singleton($class, $class);
        }

        $instance = $this->container->get($class);

        return \Closure::fromCallable([$instance, $action]);
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
     * @param string $namespace
     * @param string $class
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function verifyActionClass(string $namespace = '', string $class): string
    {
        if (class_exists($class)) {
            return $class;
        }

        if (class_exists($namespace . '\\' . $class)) {
            return $namespace . '\\' . $class;
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
