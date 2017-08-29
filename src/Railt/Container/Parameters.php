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
 * Class FlushParameters
 * @package Railt\Container
 */
class Parameters extends Container
{
    /**
     * Parameters constructor.
     * @param array $parameters
     * @throws \ReflectionException
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct();
        $this->with($parameters);
    }

    /**
     * @param object $object
     * @return \Traversable|string[]
     * @throws \ReflectionException
     */
    private function getObjectAliases($object): \Traversable
    {
        yield from $this->getClassAliases(get_class($object));
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
     * @param array $parameters
     * @return Parameters
     * @throws \ReflectionException
     */
    public function with(array $parameters = []): Parameters
    {
        foreach ($parameters as $key => $value) {
            $this->add($value);

            if (!is_int($key)) {
                $this->bind($key, $value);
            }
        }

        return $this;
    }

    /**
     * @param object|mixed $value
     * @return Parameters
     * @throws \ReflectionException
     */
    public function add($value): Parameters
    {
        switch (true) {
            case is_object($value):
                foreach ($this->getObjectAliases($value) as $alias) {
                    $this->bind($alias, $value);
                }
                break;
        }

        return $this;
    }
}
