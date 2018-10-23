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
 * Class Buffer
 */
class Buffer implements BufferInterface, \Countable
{
    /**
     * @var \Traversable|\Generator|\Iterator
     * @read-only
     */
    protected $iterator;

    /**
     * @var int
     * @read-only
     */
    protected $size;

    /**
     * @var \SplDoublyLinkedList
     */
    private $values;

    /**
     * Buffer constructor.
     * @param \Traversable $iterator
     * @param int $size
     */
    public function __construct(\Traversable $iterator, int $size = 10)
    {
        \assert($size > 0, 'Buffer size must be greater than 0');
        \assert($size <= \PHP_INT_MAX, 'Buffer size must less than ' . \PHP_INT_MAX);

        $this->size     = $size;
        $this->iterator = $iterator;

        $this->values = new \SplDoublyLinkedList();

        $this->next();
    }

    /**
     * @return mixed
     */
    public function top()
    {
        return $this->values->top();
    }

    /**
     * @return mixed
     */
    public function bottom()
    {
        return $this->values->bottom();
    }

    /**
     * @return void
     */
    public function previous(): void
    {
        $this->values->prev();
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->values->current();
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        if ($this->iterator->valid()) {
            $this->values->push($this->iterator->current());

            $this->iterator->next();

            if ($this->values->count() > $this->size) {
                $this->values->shift();
            }
        }

        $this->values->next();
    }

    /**
     * @return mixed|int|string
     */
    public function key()
    {
        return $this->values->current();
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->values->valid();
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->values->rewind();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->values->count();
    }
}
