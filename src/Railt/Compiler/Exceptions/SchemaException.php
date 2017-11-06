<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Exceptions;

use Railt\Compiler\Kernel\CallStack;

/**
 * Class SchemaException
 */
abstract class SchemaException extends \RuntimeException
{
    /**
     * @var int
     */
    private $column = 0;

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @param CallStack $trace
     * @return void
     */
    public function withStack(CallStack $trace): void
    {
        $this->stack = $trace;

        $info = $trace->getLastDefinitionInfo();

        $this->column = $info['column'];
        $this->file = $info['file'];
        $this->line = $info['line'];
        $this->trace = $this->stack->toArray();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->stack !== null) {
            return \get_class($this) . ': ' . $this->getMessage() .
                ' in ' . $this->file . ':' . $this->line . ':' . $this->column . PHP_EOL .
                'Stack trace:' . PHP_EOL . $this->stack->render();
        }

        return parent::__toString();
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }
}
