<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Exceptions;

use Illuminate\Support\Str;
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
        parent::__construct(Str::ucfirst($message), 0, $previous);

        $this->stack = $stack;

        $info = $stack->getLastDefinitionInfo();

        $this->column = $info['column'] ?? 0;
        $this->file   = $info['file'] ?? $this->file;
        $this->line   = $info['line'] ?? $this->line;

        $this->trace = $this->stack->toArray();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $result = $this->getInfo() . PHP_EOL;

        [$graphQLStack, $phpStack] = [
            'GraphQL Stack Trace:' . PHP_EOL .
            '%s' . PHP_EOL .
            'PHP Stack Trace:' . PHP_EOL .
            '%s',

            'Stack Trace:' . PHP_EOL .
            '%s',
        ];

        return $result . (
            \count($this->stack) > 0
                ? \sprintf($graphQLStack, $this->stack->render(), $this->getTraceAsString())
                : \sprintf($phpStack, $this->getTraceAsString())
            );
    }

    /**
     * @return string
     */
    public function getInfo(): string
    {
        return \vsprintf('%s: %s in %s:%d:%d', [
            \class_basename($this),
            $this->getMessage(),
            $this->getFile(),
            $this->getLine(),
            $this->getColumn(),
        ]);
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'message' => $this->message,
            'file'    => $this->file,
            'line'    => $this->line,
            'column'  => $this->column,
        ];
    }

    /**
     * @return array
     */
    public function getCompilerTrace(): array
    {
        return $this->trace;
    }
}
