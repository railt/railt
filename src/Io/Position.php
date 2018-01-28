<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io;

/**
 * Class Position
 */
final class Position
{
    private const INDEX_LINE            = 0x00;
    private const INDEX_COLUMN          = 0x01;
    private const INDEX_AFFECTED_SOURCE = 0x02;

    /**
     * @var int
     */
    private $line = 0;

    /**
     * @var int
     */
    private $column = 0;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var string
     */
    private $affectedSourceLine = '';

    /**
     * Position constructor.
     * @param string $sources
     * @param int $offset
     */
    public function __construct(string $sources, int $offset)
    {
        [
            self::INDEX_LINE            => $this->line,
            self::INDEX_COLUMN          => $this->column,
            self::INDEX_AFFECTED_SOURCE => $this->affectedSourceLine,
        ] = $this->getInformation($sources, $offset);
    }

    /**
     * Returns information about the error location: line, column and affected text lines.
     *
     * @param string $sources The source text in which we search for a line and a column
     * @param int $bytesOffset Offset in bytes relative to the beginning of the source text
     * @return array
     */
    private function getInformation(string $sources, int $bytesOffset): array
    {
        $trace  = [];
        $result = [
            self::INDEX_LINE            => 1,
            self::INDEX_COLUMN          => 0,
            self::INDEX_AFFECTED_SOURCE => '',
        ];

        $current = 0;

        foreach (\explode("\n", $sources) as $line => $text) {
            $previous = $current;
            $current += \strlen($text) + 1;
            $trace[]  = $text;

            if ($current > $bytesOffset) {
                return [
                    self::INDEX_LINE            => $line + 1,
                    self::INDEX_COLUMN          => $bytesOffset - $previous,
                    self::INDEX_AFFECTED_SOURCE => $this->parseAffectedSourceLine($trace),
                ];
            }
        }

        return $result;
    }

    /**
     * Returns the last line with an error. If the error occurred on
     * the line where there is no visible part, before complements
     * it with the previous ones.
     *
     * @param array|string[] $textLines List of text lines
     * @return string
     */
    private function parseAffectedSourceLine(array $textLines): string
    {
        $result = '';
        $i      = 0;

        while (\count($textLines) && ++$i) {
            $textLine = \array_pop($textLines);
            $result   = $textLine . ($i > 1 ? "\n" . $result : '');

            if (\trim($textLine)) {
                break;
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getAffectedSourceLine(): string
    {
        return $this->affectedSourceLine;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}
