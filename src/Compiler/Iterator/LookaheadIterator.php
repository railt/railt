<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Iterator;

/**
 * Class LookaheadIterator
 */
class LookaheadIterator extends \IteratorIterator
{
    /**
     * Current iterator.
     *
     * @var \Iterator
     */
    protected $_iterator;

    /**
     * Current key.
     *
     * @var mixed
     */
    protected $_key = 0;

    /**
     * Current value.
     *
     * @var mixed
     */
    protected $_current;

    /**
     * Whether the current element is valid or not.
     *
     * @var bool
     */
    protected $_valid = false;

    /**
     * LookaheadIterator constructor.
     * @param \Traversable $iterator
     */
    public function __construct(\Traversable $iterator)
    {
        $this->_iterator = $iterator;
    }

    /**
     * Return the current element.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->_current;
    }

    /**
     * Return the key of the current element.
     *
     * @return mixed
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * Rewind the iterator to the first element.
     *
     * @return void
     */
    public function rewind()
    {
        $out = $this->getInnerIterator()->rewind();
        $this->next();

        return $out;
    }

    /**
     * Get inner iterator.
     *
     * @return \Iterator
     */
    public function getInnerIterator()
    {
        return $this->_iterator;
    }

    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next()
    {
        $innerIterator = $this->getInnerIterator();
        $this->_valid  = $innerIterator->valid();

        if (false === $this->_valid) {
            return;
        }

        $this->_key     = $innerIterator->key();
        $this->_current = $innerIterator->current();

        return $innerIterator->next();
    }

    /**
     * Check if current position is valid.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->_valid;
    }

    /**
     * Check whether there is a next element.
     *
     * @return bool
     */
    public function hasNext()
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
