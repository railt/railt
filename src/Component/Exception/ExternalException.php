<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @noinspection TraitsPropertiesConflictsInspection
 */
declare(strict_types=1);

namespace Railt\Component\Exception;

use Railt\Component\Io\Readable;
use Railt\Component\Position\Highlight;
use Railt\Component\Position\HighlightInterface;
use Railt\Component\Exception\Trace\ItemInterface;
use Railt\Component\Exception\Trace\ObjectItemInterface;
use Railt\Component\Exception\Trace\FunctionItemInterface;
use Railt\Component\Position\PositionInterface;

/**
 * Class ExternalException
 */
class ExternalException extends \Exception implements ExternalExceptionInterface
{
    use ExternalExceptionAwareTrait;

    /**
     * @var string
     */
    public const EXCEPTION_FIELD_MESSAGE = 'message';

    /**
     * @var string
     */
    public const EXCEPTION_FIELD_CODE = 'code';

    /**
     * @var string
     */
    public const EXCEPTION_FIELD_FILE = 'file';

    /**
     * @var string
     */
    public const EXCEPTION_FIELD_LINE = 'line';

    /**
     * @var string
     */
    public const EXCEPTION_FIELD_COLUMN = 'column';

    /**
     * @var string
     */
    public const EXCEPTION_FIELD_TRACE = 'trace';

    /**
     * @var string
     */
    protected const TEMPLATE_TRACE_HEADER = 'Stack trace:';

    /**
     * @var string
     */
    protected const TEMPLATE_CODE_HEADER = 'Source code:';

    /**
     * @var string
     */
    protected const HEADER_WITH_MESSAGE = '%s: %s in %s:%d';

    /**
     * @var string
     */
    protected const HEADER_WITHOUT_MESSAGE = '%s in %s:%d';

    /**
     * @var Trace
     */
    protected $trace;

    /**
     * @var HighlightInterface|null
     */
    private $highlight;

    /**
     * ExternalException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->trace = new Trace($this->getTrace());
    }

    /**
     * @param Readable $file
     * @param int $offsetOrLine
     * @param int|null $column
     * @return ExternalExceptionInterface|$this
     */
    public function throwsIn(Readable $file, int $offsetOrLine = 0, int $column = null): ExternalExceptionInterface
    {
        if ($column === null) {
            $position = $file->getPosition($offsetOrLine);

            [$offsetOrLine, $column] = [$position->getLine(), $position->getColumn()];
        }

        $this->highlight = new Highlight($file, $offsetOrLine);

        return $this->withFile($file->getPathname())->withLine($offsetOrLine)->withColumn($column);
    }

    /**
     * @param \Throwable $exception
     * @return ExternalExceptionInterface|$this
     */
    public function from(\Throwable $exception): ExternalExceptionInterface
    {
        $this->file = $exception->getFile();
        $this->line = $exception->getLine();

        if ($exception instanceof PositionInterface) {
            $this->column = $exception->getColumn();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            static::EXCEPTION_FIELD_MESSAGE => $this->getMessage(),
            static::EXCEPTION_FIELD_CODE    => $this->getCode(),
            static::EXCEPTION_FIELD_FILE    => $this->getFile(),
            static::EXCEPTION_FIELD_LINE    => $this->getLine(),
            static::EXCEPTION_FIELD_COLUMN  => $this->getColumn(),
            static::EXCEPTION_FIELD_TRACE   => $this->trace,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return \implode(\PHP_EOL, \array_filter([
            $this->renderHeader(),
            $this->renderCode(),
            $this->renderTrace(),
        ]));
    }

    /**
     * @return string
     */
    private function renderHeader(): string
    {
        $suffix = [$this->getFile(), $this->getLine()];

        if ($this->getMessage()) {
            return \sprintf(static::HEADER_WITH_MESSAGE, \get_class($this), $this->getMessage(), ...$suffix);
        }

        return \sprintf(static::HEADER_WITHOUT_MESSAGE, \get_class($this), ...$suffix);
    }

    /**
     * @return string
     */
    private function renderCode(): string
    {
        if (! $this->highlight) {
            return '';
        }

        $code = $this->highlight->renderWithMessage($this->getMessage(), $this->column);

        return \implode(\PHP_EOL, [static::TEMPLATE_CODE_HEADER, $code]);
    }

    /**
     * @return string
     */
    private function renderTrace(): string
    {
        return \implode(\PHP_EOL, [static::TEMPLATE_TRACE_HEADER, $this->trace->toString()]);
    }

    /**
     * @param ItemInterface|FunctionItemInterface|ObjectItemInterface $item
     * @return ItemInterface|FunctionItemInterface|ObjectItemInterface
     */
    public function withTrace(ItemInterface $item): ItemInterface
    {
        $this->trace->withTrace($item);

        return $item;
    }
}
