<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Output;

/**
 * Trait HasData
 * @mixin ProvideData
 */
trait HasData
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param string $key
     * @param null $default
     * @return array|mixed
     */
    public function get(string $key, $default = null)
    {
        /**
         * Support sampling from an array using the helper of Illuminate Framework.
         * @see https://laravel.com/docs/5.7/helpers#method-array-get
         */
        if (\function_exists('\\array_get')) {
            return \array_get($this->data, $key, $default);
        }

        return $this->data[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return ProvideData|$this
     */
    public function with(string $key, $data): ProvideData
    {
        /**
         * Support insertion into an array using the helper of Illuminate Framework.
         * @see https://laravel.com/docs/5.7/helpers#method-array-set
         */
        if (\function_exists('\\array_set')) {
            \array_set($this->data, $key, $data);

            return $this;
        }

        $this->data[$key] = $data;

        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        /**
         * Support for checking an element in an array when used the helper of Illuminate Framework.
         * @see https://laravel.com/docs/5.7/helpers#method-array-has
         */
        if (\function_exists('\\array_has')) {
            return \array_has($this->data, $key);
        }

        return isset($this->data[$key]) || \array_key_exists($key, $this->data);
    }

    /**
     * @param ProvideData $data
     * @return ProvideData|$this
     */
    public function withData(ProvideData $data): ProvideData
    {
        $this->data = \array_merge_recursive($this->data, $data->all());

        return $this;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->data);
    }
}
