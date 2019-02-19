<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Config;

use Illuminate\Support\Arr;

/**
 * Class Repository
 */
class Repository implements RepositoryInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * Repository constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @param RepositoryInterface $repository
     */
    public function mergeWith(RepositoryInterface $repository): void
    {
        $this->config = \array_merge_recursive($this->config, $repository->all());
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->config;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        Arr::set($this->config, $key, $value);
    }

    /**
     * @param string $key
     */
    public function remove(string $key): void
    {
        Arr::forget($this->config, $key);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }
}
