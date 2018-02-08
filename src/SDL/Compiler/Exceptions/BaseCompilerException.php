<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Exceptions;

use Railt\SDL\Compiler\Runtime\CallStackInterface;
use Railt\SDL\Compiler\Runtime\CallStackRenderer;

/**
 * An internal compiler error exception.
 */
abstract class BaseCompilerException extends \DomainException implements CompilerException
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
     * @param CallStackInterface $stack
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, CallStackInterface $stack, \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->renderer = new CallStackRenderer($stack, \debug_backtrace());

        $latest = $this->renderer->getLastRenderer();

        $this->file = $latest->getFile();
        $this->line = $latest->getLine();
        $this->column = $latest->getColumn();
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * @return string
     */
    private function getHeader(): string
    {
        return \vsprintf('%s: %s %s', [
            static::class,
            $this->getMessage(),
            $this->renderer->getLastRenderer()->toMessageString()
        ]);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $result[] = $this->getHeader();

        foreach ($this->renderer->getTrace() as $i => $item) {
            $result[] = $item->toTraceString($i);
        }

        return \implode(\PHP_EOL, $result);
    }
}
