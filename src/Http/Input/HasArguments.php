<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

/**
 * Trait HasArguments
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
     * @return null
     */
    public function get(string $argument, $default = null)
    {
        /**
         * Support sampling from an array using the helper of Illuminate Framework.
         * @see https://laravel.com/docs/5.7/helpers#method-array-get
         */
        if (\function_exists('\\array_get')) {
            return \array_get($this->arguments, $argument, $default);
        }

        return $this->arguments[$argument] ?? $default;
    }

    /**
     * @param string $argument
     * @return bool
     */
    public function has(string $argument): bool
    {
        /**
         * Support for checking an element in an array when used the helper of Illuminate Framework.
         * @see https://laravel.com/docs/5.7/helpers#method-array-has
         */
        if (\function_exists('\\array_has')) {
            return \array_has($this->arguments, $argument);
        }

        return isset($this->arguments[$argument]) || \array_key_exists($argument, $this->arguments);
    }

    /**
     * @param string $argument
     * @param mixed $value
     * @param bool $rewrite
     * @return ProvideArguments|$this
     */
    public function withArgument(string $argument, $value, bool $rewrite = false): ProvideArguments
    {
        if ($rewrite || ! $this->has($argument)) {
            /**
             * Support insertion into an array using the helper of Illuminate Framework.
             * @see https://laravel.com/docs/5.7/helpers#method-array-set
             */
            if (\function_exists('\\array_set')) {
                \array_set($this->arguments, $argument, $value);

                return $this;
            }

            $this->arguments[$argument] = $value;
        }

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

    public function offsetExists($offset): void
    {
        throw new \LogicException('The ' . __METHOD__ . ' not implemented yet');
    }

    public function offsetGet($offset): void
    {
        throw new \LogicException('The ' . __METHOD__ . ' not implemented yet');
    }

    public function offsetSet($offset, $value): void
    {
        throw new \LogicException('The ' . __METHOD__ . ' not implemented yet');
    }

    public function offsetUnset($offset): void
    {
    }
}
