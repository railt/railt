<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Position;

/**
 * Class Position
 */
class Position implements ProvidesOffset, PositionInterface
{
    /**
     * @var string
     */
    protected const NEW_LINE_DELIMITER = "\n";

    /**
     * @var int
     */
    protected const MIN_CODE_LINE = 1;

    /**
     * @var int
     */
    protected const MIN_CODE_COLUMN = 1;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $line;

    /**
     * @var int
     */
    private $column;

    /**
     * Position constructor.
     *
     * @param int $offset
     * @param int $line
     * @param int $column
     */
    public function __construct(int $offset = 0, int $line = 1, int $column = 1)
    {
        $this->offset = $offset;
        $this->line   = \max($line, static::MIN_CODE_LINE);
        $this->column = \max($column, static::MIN_CODE_COLUMN);
    }

    /**
     * @param string $sources
     * @param int $offset
     * @return Position|$this
     */
    public static function fromOffset(string $sources, int $offset = 0): self
    {
        //
        // Format the offset so that it does not exceed the allowable text
        // size and is not less than zero.
        //
        $offset = \max(0, \min(\strlen($sources), $offset + 1));

        //
        // The number of occurrences of line breaks found in the
        // desired text slice.
        //
        $lines = \substr_count($sources, static::NEW_LINE_DELIMITER, 0, $offset);

        //
        // Go through the last line before the first occurrence
        // of line break. This value will be a column.
        //
        for ($i = $offset - 1, $column = 0; $i > 0 && $sources[$i] !== static::NEW_LINE_DELIMITER; --$i) {
            ++$column;
        }

        return new static($offset, $lines + 1, $column + 1);
    }

    /**
     * @param string $sources
     * @param int $line
     * @param int $column
     * @return Position|$this
     */
    public static function fromPosition(string $sources, int $line = 1, int $column = 1): self
    {
        [$line, $column] = [\max(1, $line), \max(1, $column)];

        // TODO Extract offset

        return new static(0, $line, $column);
    }

    /**
     * @inheritdoc
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @inheritdoc
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @inheritdoc
     */
    public function getColumn(): int
    {
        return $this->column;
    }
}
