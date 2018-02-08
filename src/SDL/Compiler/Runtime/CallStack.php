<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Runtime;

use Railt\Events\Observer;
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * Class CallStack
 */
class CallStack implements CallStackInterface, \IteratorAggregate
{
    use Observer;

    /**
     * @var \SplStack|Record[]
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
     * @return void
     */
    public function __clone()
    {
        $this->stack = clone $this->stack;
    }

    /**
     * @param Record[] ...$records
     * @return CallStack|$this|static
     */
    public function push(Record ...$records): CallStackInterface
    {
        foreach ($records as $record) {
            $this->stack->push($this->notify($record, true));
        }

        return $this;
    }

    /**
     * @return ?Record
     */
    public function pop(): ?Record
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
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        while ($this->stack->count() > 0) {
            yield $this->pop();
        }
    }
}
