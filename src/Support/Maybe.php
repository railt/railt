<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Support;

/**
 * Class Maybe
 */
class Maybe
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * Maybe constructor.
     *
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments = [])
    {
        return \is_object($this->value) ? $this->value->{$name}(...$arguments) : null;
    }

    /**
     * @param mixed ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        return \is_callable($this->value) ? ($this->value)(...$arguments) : null;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return \is_object($this->value) ? $this->value->{$name} : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        if (\is_object($this->value)) {
            $this->value->{$name} = $value;
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        switch (true) {
            case \is_object($this->value):
                return isset($this->value->{$name});

            case \is_array($this->value):
            case $this->value instanceof \ArrayAccess:
                return isset($this->value[$name]);
        }

        return false;
    }

    /**
     * @param string $name
     */
    public function __unset(string $name): void
    {
        if (\is_object($this->value)) {
            unset($this->value->{$name});
        }
    }
}
