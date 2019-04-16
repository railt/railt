<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Compiler\Grammar;

/**
 * Class LookaheadIterator
 */
class LookaheadIterator extends \IteratorIterator
{
    /**
     * Current key.
     *
     * @var mixed
     */
    protected $key = 0;

    /**
     * Current value.
     *
     * @var mixed
     */
    protected $current;

    /**
     * Whether the current element is valid or not.
     *
     * @var bool
     */
    protected $valid = false;

    /**
     * LookaheadIterator constructor.
     *
     * @param iterable $iterator
     */
    public function __construct(iterable $iterator)
    {
        parent::__construct($this->toIterator($iterator));
        $this->rewind();
    }

    /**
     * @param iterable $iterable
     * @return \Traversable
     */
    private function toIterator(iterable $iterable): \Traversable
    {
        return $iterable instanceof \Traversable
            ? $iterable
            : new \ArrayIterator($iterable);
    }

    /**
     * Rewind the iterator to the first element.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->getInnerIterator()->rewind();

        $this->next();
    }

    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next(): void
    {
        $innerIterator = $this->getInnerIterator();
        $this->valid = $innerIterator->valid();

        if ($this->valid === false) {
            return;
        }

        $this->key = $innerIterator->key();
        $this->current = $innerIterator->current();

        $innerIterator->next();
    }

    /**
     * Return the current element.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Return the key of the current element.
     *
     * @return mixed
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Check if current position is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->valid;
    }

    /**
     * Check whether there is a next element.
     *
     * @return bool
     */
    public function hasNext(): bool
    {
        return $this->getInnerIterator()->valid();
    }

    /**
     * Get next value.
     *
     * @return mixed
     */
    public function getNext()
    {
        return $this->getInnerIterator()->current();
    }

    /**
     * Get next key.
     *
     * @return mixed
     */
    public function getNextKey()
    {
        return $this->getInnerIterator()->key();
    }
}
