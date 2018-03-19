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
 * Class Filter
 */
class FilterIterator implements \IteratorAggregate
{
    /**
     * @var \Traversable
     */
    private $iterator;

    /**
     * @var \Closure[]
     */
    private $filters = [];

    /**
     * FilterIterator constructor.
     * @param \Traversable $iterator
     */
    public function __construct(\Traversable $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * @param \Closure $filter
     * @return FilterIterator
     */
    public function where(\Closure $filter): self
    {
        $this->filters[] = function (...$args) use ($filter) {
            return ! $filter(...$args);
        };

        return $this;
    }

    /**
     * @param \Closure $filter
     * @return FilterIterator
     */
    public function except(\Closure $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->iterator as $id => $value) {
            foreach ($this->filters as $filter) {
                if ($filter($value, $id)) {
                    continue 2;
                }
            }

            yield $id => $value;
        }
    }
}
