<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Config;

/**
 * Class Repository
 */
class Repository implements RepositoryInterface
{
    /**
     * @var string
     */
    protected const DEPTH_DELIMITER = '.';

    /**
     * @var array
     */
    protected array $items = [];

    /**
     * @var string
     */
    private string $delimiter;

    /**
     * Repository constructor.
     *
     * @param array $items
     * @param string $delimiter
     */
    public function __construct(array $items = [], string $delimiter = self::DEPTH_DELIMITER)
    {
        $this->items = $items;
        $this->delimiter = $delimiter;
    }

    /**
     * @param string $key
     * @return array|string[]
     */
    protected function chunks(string $key): array
    {
        return \array_filter(\explode($this->delimiter, $key), fn (string $chunk) => $chunk !== '');
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, $default = null)
    {
        $result = $this->items;

        foreach ($this->chunks($key) as $chunk) {
            if (! \is_array($result) || ! \array_key_exists($chunk, $result)) {
                return $default;
            }

            $result = $result[$chunk];
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $key): bool
    {
        $result = $this->items;

        foreach (\explode($this->delimiter, $key) as $chunk) {
            if (! \is_array($result) || ! \array_key_exists($key, $result)) {
                return false;
            }

            $result = $result[$chunk];
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->all());
    }
}
