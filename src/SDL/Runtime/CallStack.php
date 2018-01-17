<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Runtime;

use Railt\Reflection\Contracts\Definitions\Definition;

/**
 * Class CallStack
 */
class CallStack implements CallStackInterface, \IteratorAggregate
{
    /**
     * @var \SplStack|Definition[]
     */
    protected $stack;

    /**
     * CallStack constructor.
     */
    public function __construct()
    {
        $this->stack = new \SplStack();
    }

    /**
     * @param Definition[] ...$definitions
     * @return CallStack|$this|static
     */
    public function push(Definition ...$definitions): CallStackInterface
    {
        foreach ($definitions as $definition) {
            $this->stack->push($definition);
        }

        return $this;
    }

    /**
     * @param int $size
     * @return CallStack|$this|static
     */
    public function pop(int $size = 1): CallStackInterface
    {
        for ($i = 0; $i < $size; ++$i) {
            $this->last();
        }

        return $this;
    }

    /**
     * @return Definition|null
     */
    public function last(): ?Definition
    {
        return $this->stack->count() > 0 ? $this->stack->pop() : null;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->stack->count();
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        while ($this->stack->count() > 0) {
            yield $this->pop();
        }
    }
}
