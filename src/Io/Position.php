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
final class Position implements PositionInterface
{
    /**
     * @var int|null
     */
    private $line;

    /**
     * @var int|null
     */
    private $column;

    /**
     * @var int
     */
    private $offset;

    /**
     * Position constructor.
     *
     * @param string $sources
     * @param int $offset
     */
    public function __construct(string $sources, int $offset)
    {
        $this->offset = $this->normalizeOffset($sources, $offset);

        $substr = \substr($sources, 0, $this->offset);

        $this->line = $this->readLineByOffset($substr);
        $this->column = $this->readColumnByOffset($substr);
    }

    /**
     * @param string $sources
     * @param int $bytesOffset
     * @return int
     */
    private function normalizeOffset(string $sources, int $bytesOffset): int
    {
        return \max(0, \min(\strlen($sources), $bytesOffset));
    }

    /**
     * @param string $sources
     * @return int
     */
    private function readLineByOffset(string $sources): int
    {
        return \substr_count($sources, "\n") + 1;
    }

    /**
     * @param string $sources
     * @return int
     */
    private function readColumnByOffset(string $sources): int
    {
        $lines = \explode("\n", $sources);

        return \strlen($lines[\count($lines) - 1]) + 1;
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

    /**
     * @return array|int[]
     */
    public function __debugInfo(): array
    {
        return [
            'line'   => $this->line,
            'column' => $this->column,
            'offset' => $this->offset,
        ];
    }
}
