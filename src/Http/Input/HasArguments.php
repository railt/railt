<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

use Illuminate\Support\Arr;

/**
 * Trait HasArguments
 *
 * @mixin ProvideArguments
 */
trait HasArguments
{
    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->arguments;
    }

    /**
     * @param string $argument
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $argument, $default = null)
    {
        return Arr::get($this->arguments, $argument, $default);
    }

    /**
     * @param string $argument
     * @param \Closure|null $then
     * @return bool
     */
    public function provides(string $argument, \Closure $then = null): bool
    {
        $value = $this->get($argument);

        if ($value !== null) {
            $then($value, $this);

            return true;
        }

        return false;
    }

    /**
     * @param string $argument
     * @return bool
     */
    public function has(string $argument): bool
    {
        return Arr::has($this->arguments, $argument);
    }

    /**
     * @param string $argument
     * @param mixed $value
     * @param bool $rewrite
     * @return ProvideArguments|$this
     */
    public function withArgument(string $argument, $value, bool $rewrite = false): ProvideArguments
    {
        if ($rewrite) {
            Arr::set($this->arguments, $argument, $value);
        } else {
            Arr::add($this->arguments, $argument, $value);
        }

        return $this;
    }

    /**
     * @param string $argument
     * @return ProvideArguments
     */
    public function withoutArgument(string $argument): ProvideArguments
    {
        Arr::forget($this->arguments, $argument);

        return $this;
    }

    /**
     * @param array $arguments
     * @param bool $rewrite
     * @return ProvideArguments|$this
     */
    public function withArguments(array $arguments, bool $rewrite = false): ProvideArguments
    {
        foreach ($arguments as $name => $value) {
            $this->withArgument($name, $value, $rewrite);
        }

        return $this;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->arguments);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->arguments);
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->has((string)$offset);
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get((string)$offset);
    }

    /**
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->withArgument((string)$offset, $value);
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset): void
    {
        $this->withoutArgument((string)$offset);
    }
}
