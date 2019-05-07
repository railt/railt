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
 * Class Item
 */
class Item implements ItemInterface, MutableItemInterface, \JsonSerializable, \ArrayAccess
{
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
    private const INTERNAL_FUNCTION = '[internal function]';

    /**
     * @var string
     */
    protected $file;

    /**
     * @var int
     */
    protected $line;

    /**
     * @var int
     */
    protected $column;

    /**
     * Item constructor.
     *
     * @param string $file
     * @param int $line
     * @param int $column
     */
    public function __construct(string $file, int $line = 1, int $column = 1)
    {
        $this->file = $file;
        $this->line = \max($line, 1);
        $this->column = \max($column, 1);
    }

    /**
     * @return bool
     */
    protected function isInternal(): bool
    {
        return $this->getFile() === self::INTERNAL_FUNCTION;
    }

    /**
     * @param string $file
     * @param int $line
     * @param int $column
     * @return Item|$this
     */
    public static function new(string $file, int $line = 1, int $column = 1): self
    {
        return new static($file, $line, $column);
    }

    /**
     * @param array $trace
     * @return ItemInterface|$this
     */
    public static function fromArray(array $trace): ItemInterface
    {
        [$line, $column] = static::positionFromArray($trace);

        return static::new($trace[static::EXCEPTION_FIELD_FILE] ?? self::INTERNAL_FUNCTION, $line, $column);
    }

    /**
     * @param array $trace
     * @return array
     */
    protected static function positionFromArray(array $trace): array
    {
        return [$trace[static::EXCEPTION_FIELD_LINE] ?? 1, $trace[static::EXCEPTION_FIELD_COLUMN] ?? 1];
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return MutableItemInterface|$this
     */
    public function withFile(string $file): MutableItemInterface
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @param int $line
     * @return MutableItemInterface|$this
     */
    public function withLine(int $line): MutableItemInterface
    {
        $this->line = $line;

        return $this;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * @param int $column
     * @return MutableItemInterface|$this
     */
    public function withColumn(int $column): MutableItemInterface
    {
        $this->column = $column;

        return $this;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            static::EXCEPTION_FIELD_FILE   => $this->getFile(),
            static::EXCEPTION_FIELD_LINE   => $this->getLine(),
            static::EXCEPTION_FIELD_COLUMN => $this->getColumn(),
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
        return $this->fileToString();
    }

    /**
     * @return string
     */
    protected function fileToString(): string
    {
        if ($this->getFile() === self::INTERNAL_FUNCTION) {
            return self::INTERNAL_FUNCTION;
        }

        return \sprintf('%s(%d)', $this->getFile(), $this->getLine());
    }

    /**
     * @noinspection MagicMethodsValidityInspection
     * @since PHP 7.4
     * @return array
     */
    public function __serialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        \assert(\is_string($offset));

        $data = $this->toArray();

        return \array_key_exists($offset, $data);
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        \assert(\is_string($offset));

        $data = $this->toArray();

        return $data[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws \LogicException
     */
    public function offsetSet($offset, $value): void
    {
        throw new \LogicException(static::class . ' is immutable');
    }

    /**
     * @param mixed $offset
     * @throws \LogicException
     */
    public function offsetUnset($offset): void
    {
        throw new \LogicException(static::class . ' is immutable');
    }
}
