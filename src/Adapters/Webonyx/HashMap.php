<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx;

use GraphQL\Type\Definition\Type;
use Serafim\Railgun\Types\TypeInterface;

/**
 * Class HashMap
 * @package Serafim\Railgun\Adapters\Webonyx
 */
class HashMap implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @param TypeInterface $type
     * @return string
     */
    public function getHash(TypeInterface $type): string
    {
        return spl_object_hash($type);
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function has(TypeInterface $type): bool
    {
        return isset($this->items[$this->getHash($type)]);
    }

    /**
     * @param TypeInterface $type
     * @return Type|null
     */
    public function get(TypeInterface $type): ?Type
    {
        return $this->items[$this->getHash($type)] ?? null;
    }

    /**
     * @param TypeInterface $type
     * @param Type $webonyx
     * @return HashMap
     */
    public function set(TypeInterface $type, Type $webonyx): HashMap
    {
        $this->items[$this->getHash($type)] = $webonyx;

        return $this;
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
