<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Exceptions;

use Illuminate\Support\Str;
use Railt\SDL\Runtime\CallStack;
use Railt\SDL\Runtime\CallStackRenderer;

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
     * @var CallStackRenderer
     */
    private $renderer;

    /**
     * BaseSchemaException constructor.
     * @param string $message
     * @param CallStack $stack
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, CallStack $stack, \Throwable $previous = null)
    {
        parent::__construct(Str::ucfirst($message), 0, $previous);

        $this->renderer = new CallStackRenderer($stack);

        $this->column = $this->renderer->getColumn();
        $this->file   = $this->renderer->getFile();
        $this->line   = $this->renderer->getLine();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $header = \vsprintf('%s: %s in %s:%d:%d', [
            \class_basename($this),
            $this->getMessage(),
            $this->getFile(),
            $this->getLine(),
            $this->getColumn(),
        ]);

        $result = $header . \PHP_EOL;

        if ($this->renderer->hasTrace()) {
            $result .= 'GraphQL Stack trace:' . \PHP_EOL .
                $this->getCompilerTraceAsString() . \PHP_EOL;
        }

        $result .= 'PHP Stack trace:' . \PHP_EOL .
                $this->getTraceAsString();

        return $result;
    }

    /**
     * @return array
     */
    public function getCompilerTrace(): array
    {
        return $this->renderer->getTrace();
    }

    /**
     * @return string
     */
    public function getCompilerTraceAsString(): string
    {
        return $this->renderer->getTraceAsString();
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
}
