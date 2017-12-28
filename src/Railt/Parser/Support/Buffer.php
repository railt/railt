<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Support;

/**
 * Class \Hoa\Iterator\Buffer.
 *
 * Buffer iterator: Can go backward up to a certain limit, and forward.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Buffer implements \OuterIterator
{
    /**
     * Buffer key index.
     * @const int
     */
    private const BUFFER_KEY   = 0;

    /**
     * Buffer value index.
     * @const int
     */
    private const BUFFER_VALUE = 1;

    /**
     * Current iterator.
     * @var \Iterator
     */
    protected $iterator;

    /**
     * Buffer.
     * @var \SplDoublyLinkedList
     */
    protected $buffer;

    /**
     * Maximum buffer size.
     * @var int
     */
    protected $maxSize = 1;

    /**
     * Construct.
     *
     * @param \Iterator $iterator Iterator.
     * @param int $size Buffer size.
     */
    public function __construct(\Iterator $iterator, int $size = 1024)
    {
        $this->iterator = $iterator;
        $this->maxSize  = \max($size, 1);
        $this->buffer   = new \SplDoublyLinkedList();
    }

    /**
     * Get inner iterator.
     * @return \Iterator
     */
    public function getInnerIterator(): \Iterator
    {
        return $this->iterator;
    }

    /**
     * Get buffer.
     * @return \SplDoublyLinkedList
     */
    protected function getBuffer(): \SplDoublyLinkedList
    {
        return $this->buffer;
    }

    /**
     * Get buffer size.
     * @return int
     */
    public function getBufferSize(): int
    {
        return $this->maxSize;
    }

    /**
     * Return the current element.
     * @return  mixed
     */
    public function current()
    {
        return $this->getBuffer()->current()[self::BUFFER_VALUE];
    }

    /**
     * Return the key of the current element.
     * @return mixed
     */
    public function key()
    {
        return $this->getBuffer()->current()[self::BUFFER_KEY];
    }

    /**
     * Move forward to next element.
     * @return void
     */
    public function next(): void
    {
        $innerIterator = $this->getInnerIterator();
        $buffer        = $this->getBuffer();

        $buffer->next();

        // End of the buffer, need a new value.
        if (false === $buffer->valid()) {
            for (
                $bufferSize        = \count($buffer),
                $maximumBufferSize = $this->getBufferSize();
                $bufferSize >= $maximumBufferSize;
                --$bufferSize
            ) {
                $buffer->shift();
            }

            $innerIterator->next();

            $buffer->push([
                self::BUFFER_KEY   => $innerIterator->key(),
                self::BUFFER_VALUE => $innerIterator->current()
            ]);

            // Seek to the end of the buffer.
            $buffer->setIteratorMode($buffer::IT_MODE_LIFO | $buffer::IT_MODE_KEEP);
            $buffer->rewind();
            $buffer->setIteratorMode($buffer::IT_MODE_FIFO | $buffer::IT_MODE_KEEP);
        }
    }

    /**
     * Move backward to previous element.
     * @return void
     */
    public function previous(): void
    {
        $this->getBuffer()->prev();
    }

    /**
     * Rewind the iterator to the first element.
     * @return void
     */
    public function rewind(): void
    {
        $innerIterator = $this->getInnerIterator();
        $buffer        = $this->getBuffer();

        $innerIterator->rewind();

        if (true === $buffer->isEmpty()) {
            $buffer->push([
                self::BUFFER_KEY   => $innerIterator->key(),
                self::BUFFER_VALUE => $innerIterator->current()
            ]);
        }

        $buffer->rewind();
    }

    /**
     * Check if current position is valid.
     * @return bool
     */
    public function valid(): bool
    {
        return$this->getBuffer()->valid() && $this->getInnerIterator()->valid();
    }
}
