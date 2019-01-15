<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\TokenStream;

/**
 * Creates an iterator from another iterator that will keep the results of the
 * inner iterator in memory, so that results don't have to be re-calculated.
 */
class Buffer implements \Iterator, \Countable
{
    /**
     * @var \Traversable|\Generator|\Iterator
     * @read-only
     */
    protected $inner;

    /**
     * @var int
     * @read-only
     */
    protected $size;

    /**
     * @var \SplDoublyLinkedList
     */
    private $buffer;

    /**
     * @var int
     */
    private $index = 0;

    /**
     * Buffer constructor.
     * @param iterable $iterator
     * @param int $size
     */
    public function __construct(iterable $iterator, int $size = 10)
    {
        \assert($size > 0, 'Buffer size must be greater than 0');
        \assert($size <= \PHP_INT_MAX, 'Buffer size must less than ' . \PHP_INT_MAX);

        $this->size   = $size;
        $this->inner  = $this->toGenerator($iterator);
        $this->buffer = new \SplDoublyLinkedList();

        if ($this->inner->valid()) {
            $this->next();
            $this->rewind();
        }
    }

    /**
     * @param iterable $iterator
     * @return \Generator
     */
    private function toGenerator(iterable $iterator): \Generator
    {
        foreach ($iterator as $value) {
            yield $value;
        }
    }

    /**
     * @return mixed
     */
    public function rewind(): void
    {
        $this->index = 0;
        $this->buffer->rewind();
    }

    /**
     * @return mixed
     */
    public function top()
    {
        return $this->buffer->top();
    }

    /**
     * @return mixed
     */
    public function bottom()
    {
        return $this->buffer->bottom();
    }

    /**
     * @return mixed
     */
    public function prev()
    {
        $this->buffer->prev();
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->buffer->current();
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function next()
    {
        ++$this->index;

        if ($this->inner->valid()) {
            $this->buffer->push($this->inner->current());
            $this->inner->next();

            if ($this->buffer->count() > $this->size) {
                $this->buffer->shift();
            }
        }

        $this->buffer->next();
    }

    /**
     * @return mixed|int|string
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->buffer->valid();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->buffer->count();
    }
}
