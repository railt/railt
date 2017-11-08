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
 * Class BaseSchemaException
 */
abstract class BaseSchemaException extends \LogicException implements SchemaException
{
    /**
     * @var int
     */
    private $column;

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var array
     */
    private $trace;

    /**
     * BaseSchemaException constructor.
     * @param string $message
     * @param CallStack $stack
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, CallStack $stack, \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->stack = $stack;

        $info = $stack->getLastDefinitionInfo();

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
        $eol = PHP_EOL;

        $result = \vsprintf("%s: %s in %s:%d:%d{$eol}", [
            \get_class($this),
            $this->getMessage(),
            $this->getFile(),
            $this->getLine(),
            $this->getColumn()
        ]);

        [$graphQLStack, $phpStack] = [
            "GraphQL Stack Trace:{$eol}%sPHP Stack Trace:{$eol}%s",
            "Stack Trace:{$eol}%s"
        ];

        return $result . (
            \count($this->stack) > 1
                ? \sprintf($graphQLStack, $this->stack->render(), $this->getTraceAsString())
                : \sprintf($phpStack, $this->getTraceAsString())
        );
    }

    /**
     * @return array
     */
    public function getCompilerTrace(): array
    {
        return $this->trace;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }
}
