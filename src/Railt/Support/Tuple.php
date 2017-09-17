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
 * Class Tuple
 */
class Tuple implements \ArrayAccess
{
    /**
     * @var array
     */
    private $params = [];

    /**
     * Tuple constructor.
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        foreach ($params as $param) {
            if (is_iterable($param)) {
                foreach ((array)$param as $key => $value) {
                    $this->params[$key] = $value;
                }
                continue;
            }

            $this->params[] = $param;
        }
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->params);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->params[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     * @throws \LogicException
     */
    public function offsetSet($offset, $value): void
    {
        throw new \LogicException('Can not add a new entry into immutable class ' . __CLASS__);
    }

    /**
     * @param mixed $offset
     * @return void
     * @throws \LogicException
     */
    public function offsetUnset($offset): void
    {
        throw new \LogicException('Can not remove entry from immutable class ' . __CLASS__);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->offsetGet($name);
    }
}
