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
 * Class HashMap
 * @package Spl\Map
 */
class HashMap implements \IteratorAggregate, \ArrayAccess
{
    private const PREFIX_NUMBER     = 'n:';
    private const PREFIX_BOOL       = 'b:';
    private const PREFIX_STRING     = 's:';
    private const PREFIX_RESOURCE   = 'r:';
    private const PREFIX_ARRAY      = 'a:';
    private const PREFIX_OBJECT     = 'o:';

    /**
     * @var array
     */
    protected $keys = [];

    /**
     * @var array
     */
    protected $values = [];

    /**
     * HashMap constructor.
     * @param iterable $items
     */
    public function __construct(iterable $items = [])
    {
        $this->putAll($items);
    }

    /**
     * @param iterable $map
     * @return $this|HashMap
     */
    public function putAll(iterable $map): HashMap
    {
        foreach ($map as $key => $value) {
            $this->put($key, $value);
        }

        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @param bool $rewrite
     * @return $this|HashMap
     */
    public function put($key, $value, bool $rewrite = true): HashMap
    {
        if ($rewrite || !$this->hasKey($key)) {
            $this->keys[$this->hash($key)] = $key;
            $this->values[$this->hash($key)] = $value;
        }

        return $this;
    }

    /**
     * @param mixed $key
     * @return bool
     */
    public function hasKey($key): bool
    {
        return array_key_exists($this->hash($key), $this->keys);
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function hash($value): string
    {
        switch (true) {
            case is_object($value):
                return self::PREFIX_OBJECT . spl_object_hash($value);

            case is_numeric($value):
                return self::PREFIX_NUMBER . (int)$value;

            case is_bool($value):
                return self::PREFIX_BOOL . (string)$value;

            case is_string($value):
                return self::PREFIX_STRING . $value;

            case is_resource($value):
                return self::PREFIX_RESOURCE . $value;

            case is_array($value):
                return self::PREFIX_ARRAY . md5(serialize($value));
        }

        return '0';
    }

    /**
     * @return iterable
     */
    public function keys(): iterable
    {
        foreach ($this->getIterator() as $key => $value) {
            yield $key;
        }
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->keys as $hash => $key) {
            yield $key => $this->values[$hash];
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function hasValue($value): bool
    {
        return array_key_exists($this->hash($value), $this->values);
    }

    /**
     * @return \Generator
     */
    public function values(): \Generator
    {
        foreach ($this->getIterator() as $key => $value) {
            yield $value;
        }
    }

    /**
     * @return $this|HashMap
     */
    public function clear(): HashMap
    {
        $this->keys = [];
        $this->values = [];

        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->keys);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->put($offset, $value);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->hasKey($offset);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }

    /**
     * @param mixed $key
     * @return $this
     */
    public function remove($key): HashMap
    {
        if ($this->hasKey($key)) {
            unset($this->keys[$this->hash($key)], $this->values[$this->hash($key)]);
        }

        return $this;
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->values[$this->hash($offset)] ?? null;
    }

    /**
     * @param $key
     * @param null|mixed $default
     * @return null|mixed
     */
    public function get($key, $default = null)
    {
        return $this->values[$this->hash($key)] ?? $default;
    }
}
