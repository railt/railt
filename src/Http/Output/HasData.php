<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Output;

use Illuminate\Support\Arr;

/**
 * Trait HasData
 *
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
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return ProvideData|$this
     */
    public function with(string $key, $data): ProvideData
    {
        Arr::set($this->data, $key, $data);

        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return Arr::has($this->data, $key);
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
