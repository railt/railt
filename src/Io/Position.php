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
     * Position constructor.
     * @param string $sources
     * @param int $offset
     */
    public function __construct(string $sources, int $offset)
    {
        [$this->line, $this->column, $this->offset] = $this->getInformation($sources, $offset);
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
        $line     = 0;
        $current  = 0;

        foreach (\explode("\n", $sources) as $line => $text) {
            $previous = $current;
            $current += \strlen($text) + 1;

            if ($current > $bytesOffset) {
                return [$line + 1, $bytesOffset - $previous, $bytesOffset];
            }
        }

        return [$line, 0, $current - 1];
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
