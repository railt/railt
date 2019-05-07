<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Exception\Trace;

/**
 * Interface MutableFunctionItemInterface
 */
interface MutableFunctionItemInterface
{
    /**
     * @param string $function
     * @return FunctionItemInterface|$this
     */
    public function withFunction(string $function): self;

    /**
     * @param mixed $value
     * @param int|null $index
     * @return FunctionItemInterface|$this
     */
    public function withArgument($value, int $index = null): self;

    /**
     * @param mixed ...$values
     * @return FunctionItemInterface|$this
     */
    public function withArguments(...$values): self;
}
