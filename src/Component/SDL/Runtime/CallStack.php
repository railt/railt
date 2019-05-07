<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Runtime;

use Railt\Component\SDL\Contracts\Definitions\Definition;

/**
 * Class CallStack
 */
class CallStack implements CallStackInterface, \IteratorAggregate
{
    use Observer;

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
            $this->stack->push($this->notify($definition, true));
        }

        return $this;
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

    /**
     * @return Definition|null
     */
    public function pop(): ?Definition
    {
        if ($this->count() > 0) {
            return $this->notify($this->stack->pop());
        }

        return null;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->stack->count();
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->stack = clone $this->stack;
    }
}
