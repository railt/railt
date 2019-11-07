<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Generic;

/**
 * Class ReadOnlyCollection
 */
abstract class ReadOnlyCollection extends Collection
{
    /**
     * ReadOnlyCollection constructor.
     *
     * @param \Closure $generic
     * @param array $items
     * @throws \TypeError
     */
    public function __construct(\Closure $generic, array $items)
    {
        parent::__construct($generic, $items);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     * @throws \BadMethodCallException
     */
    public function offsetSet($offset, $value): void
    {
        $message = 'Can not update item "%s" in immutable collection';

        throw new \BadMethodCallException(\sprintf($message, $offset));
    }

    /**
     * @param mixed $offset
     * @return void
     * @throws \BadMethodCallException
     */
    public function offsetUnset($offset): void
    {
        $message = 'Can not remove item "%s" in immutable collection';

        throw new \BadMethodCallException(\sprintf($message, $offset));
    }
}
