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
     * @var string
     */
    private $namespace = '';

    /**
     * StringableAction constructor.
     * @param Container $container
     * @param string $namespace
     */
    public function __construct(Container $container, string $namespace = '')
    {
        $this->container = $container;
        $this->namespace = $namespace;
    }

    /**
     * @param string $action
     * @return array
     * @throws \InvalidArgumentException
     */
    private function parse(string $action): array
    {
        [$class, $action] = $this->stringToArray($action);

        $class = $this->verifyActionClass($this->namespace, $class);

        $this->verifyActionMethod($class, $action);

        return [$class, $action];
    }

    /**
     * @param string $action
     * @return \Closure
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    public function toCallable(string $action): \Closure
    {
        [$class, $action] = $this->parse($action);

        $reflection = new \ReflectionClass($class);

        $method = $reflection->getMethod($action);

        if ($method->isStatic()) {
            return \Closure::fromCallable([$class, $action]);
        }

        $instance = $this->container->make($class);

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
