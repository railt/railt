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
class PairBuffer extends Buffer
{
    /**
     * @var \SplDoublyLinkedList
     */
    private $keys;

    /**
     * PairBuffer constructor.
     * @param \Traversable $iterator
     * @param int $size
     */
    public function __construct(\Traversable $iterator, int $size = 10)
    {
        parent::__construct($iterator, $size);

        $this->keys = new \SplDoublyLinkedList();
    }

    /**
     * @return void
     */
    public function previous(): void
    {
        $this->keys->prev();

        parent::previous();
    }

    public function next(): void
    {
        parent::next();

        if ($this->iterator->valid()) {
            $this->keys->push($this->iterator->key());

            if ($this->keys->count() > $this->size) {
                $this->keys->shift();
            }
        }

        $this->keys->next();
    }

    /**
     * @return int|mixed|string
     */
    public function key()
    {
        return $this->keys->current();
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->keys->rewind();
        parent::rewind();
    }
}
