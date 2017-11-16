<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Kernel;

use Railt\Reflection\Contracts\Definitions\Definition;

/**
 * Class Transaction
 */
class Transaction
{
    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var int
     */
    private $size = 0;

    /**
     * @var array|\Closure[]
     */
    private $invocations = [];

    /**
     * Transaction constructor.
     * @param CallStack $stack
     */
    public function __construct(CallStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * @param Definition[] ...$definitions
     * @return Transaction
     */
    public function push(Definition ...$definitions): Transaction
    {
        $this->size += \count($definitions);
        $this->stack->push(...$definitions);

        return $this;
    }

    /**
     * @param \Closure $then
     * @return Transaction
     */
    public function then(\Closure $then): Transaction
    {
        $this->invocations[] = $then;

        return $this;
    }

    /**
     * @return Transaction
     */
    public function commit(): Transaction
    {
        if ($this->size > 0) {
            $this->stack->pop($this->size);
            $this->size = 0;
        }

        return $this;
    }

    /**
     * @param \Closure|null $what
     * @return Transaction
     * @throws \Throwable
     */
    public function invoke(\Closure $what = null): Transaction
    {
        if ($what !== null) {
            $this->then($what);
        }

        foreach ($this->invocations as $invocation) {
            try {
                $invocation();
            } catch (\Throwable $e) {
                $this->commit();
                throw $e;
            }
        }

        $this->commit();

        return $this;
    }
}
