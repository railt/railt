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
 * Class BufferedIterator
 */
class BufferedIterator extends \IteratorIterator
{
    /**
     * Buffer key index.
     *
     * @const int
     */
    const BUFFER_KEY = 0;

    /**
     * Buffer value index.
     *
     * @const int
     */
    const BUFFER_VALUE = 1;

    /**
     * Current iterator.
     *
     * @var \Iterator
     */
    protected $_iterator = null;

    /**
     * Buffer.
     *
     * @var \SplDoublyLinkedList
     */
    protected $_buffer = null;

    /**
     * Maximum buffer size.
     *
     * @var int
     */
    protected $_bufferSize = 1;

    /**
     * Construct.
     *
     * @param \Traversable|\Iterator $iterator Iterator.
     * @param int $bufferSize Buffer size.
     */
    public function __construct(\Traversable $iterator, $bufferSize)
    {
        $this->_iterator   = $iterator;
        $this->_bufferSize = max($bufferSize, 1);
        $this->_buffer     = new \SplDoublyLinkedList();
    }

    /**
     * Return the current element.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->getBuffer()->current()[self::BUFFER_VALUE];
    }

    /**
     * Get buffer.
     *
     * @return \SplDoublyLinkedList
     */
    protected function getBuffer()
    {
        return $this->_buffer;
    }

    /**
     * Return the key of the current element.
     *
     * @return mixed
     */
    public function key()
    {
        return $this->getBuffer()->current()[self::BUFFER_KEY];
    }

    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next()
    {
        $innerIterator = $this->getInnerIterator();
        $buffer        = $this->getBuffer();

        $buffer->next();

        // End of the buffer, need a new value.
        if (false === $buffer->valid()) {
            for (
                $bufferSize = count($buffer),
                $maximumBufferSize = $this->getBufferSize();
                $bufferSize >= $maximumBufferSize;
                --$bufferSize
            ) {
                $buffer->shift();
            }

            $innerIterator->next();

            $buffer->push([
                self::BUFFER_KEY   => $innerIterator->key(),
                self::BUFFER_VALUE => $innerIterator->current(),
            ]);

            // Seek to the end of the buffer.
            $buffer->setIteratorMode($buffer::IT_MODE_LIFO | $buffer::IT_MODE_KEEP);
            $buffer->rewind();
            $buffer->setIteratorMode($buffer::IT_MODE_FIFO | $buffer::IT_MODE_KEEP);
        }

        return;
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
     * Get buffer size.
     *
     * @return int
     */
    public function getBufferSize()
    {
        return $this->_bufferSize;
    }

    /**
     * Move backward to previous element.
     *
     * @return void
     */
    public function previous()
    {
        $this->getBuffer()->prev();

        return;
    }

    /**
     * Rewind the iterator to the first element.
     *
     * @return void
     */
    public function rewind()
    {
        $innerIterator = $this->getInnerIterator();
        $buffer        = $this->getBuffer();

        $innerIterator->rewind();

        if (true === $buffer->isEmpty()) {
            $buffer->push([
                self::BUFFER_KEY   => $innerIterator->key(),
                self::BUFFER_VALUE => $innerIterator->current(),
            ]);
        }

        $buffer->rewind();

        return;
    }

    /**
     * Check if current position is valid.
     *
     * @return bool
     */
    public function valid()
    {
        return
            $this->getBuffer()->valid() &&
            $this->getInnerIterator()->valid();
    }
}
