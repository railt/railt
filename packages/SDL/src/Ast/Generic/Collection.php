<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Generic;

use Railt\SDL\Ast\Node;

/**
 * Class Collection
 */
abstract class Collection extends Node implements \ArrayAccess, \Countable
{
    /**
     * @var array|Node[]
     */
    private array $items = [];

    /**
     * @var \Closure|null
     */
    private ?\Closure $generic = null;

    /**
     * Collection constructor.
     *
     * @param \Closure $generic
     * @param array $items
     * @throws \TypeError
     */
    public function __construct(\Closure $generic, array $items = [])
    {
        $this->generic = $generic;

        foreach ($items as $key => $item) {
            $this->items[$key] = $this->assert($item);
        }
    }

    /**
     * @param int $name
     * @param mixed $value
     * @return void
     */
    public function __set(int $name, $value): void
    {
        $this->items[$name] = $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \TypeError
     */
    private function assert($value)
    {
        if (! ($this->generic)($value)) {
            $type = \is_object($value) ? \get_class($value) : \gettype($value);

            throw new \TypeError(\sprintf('A type %s can not be a part of %s', $type, static::class));
        }

        return $value;
    }

    /**
     * @return \Traversable|Node[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @param int|string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        \assert(\is_int($offset) || \is_string($offset));

        return \array_key_exists($offset, $this->items);
    }

    /**
     * @param int|string $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        \assert(\is_int($offset) || \is_string($offset));

        return $this->items[$offset] ?? null;
    }

    /**
     * @param int|string $offset
     * @param mixed $value
     * @return void
     * @throws \TypeError
     */
    public function offsetSet($offset, $value): void
    {
        \assert(\is_int($offset) || \is_string($offset));

        $this->items[$offset] = $this->assert($value);
    }

    /**
     * @param int|string $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        \assert(\is_int($offset) || \is_string($offset));

        unset($this->items[$offset]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
