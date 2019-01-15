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
use Railt\SDL\Runtime\CallStackInterface;
use Railt\SDL\Runtime\CallStackRenderer;

/**
 * Class BaseSchemaException
 */
abstract class BaseSchemaException extends \DomainException implements SchemaException
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
        parent::__construct(Str::ucfirst($message), 0, $previous);

        $this->renderer = new CallStackRenderer($stack, \debug_backtrace());

        $latest = $this->renderer->getLastRenderer();

        $this->file   = $latest->getFile();
        $this->line   = $latest->getLine();
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
            $this->renderer->getLastRenderer()->toMessageString(),
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
