<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Exception;

/**
 * Trait Support
 */
trait Support
{
    /**
     * This is an auxiliary method that returns the line number, shift,
     * and line of code by shift relative to the beginning of the file.
     *
     * @param string $input The source code
     * @param int $bytesOffset Offset in bytes
     * @return  array
     */
    protected function getErrorPositionByOffset(string $input, int $bytesOffset): array
    {
        $result = $this->getErrorInfo($input, $bytesOffset);
        $code   = $this->getAffectedCodeAsString($result['trace']);
        $column = $this->getMbColumnPosition($code, $result['column']);

        return [
            'line'      => $result['line'],
            'code'      => $code,
            'column'    => $column,
            'highlight' => $this->getStringHighlighting($column),
        ];
    }

    /**
     * Returns the last line with an error. If the error occurred on
     * the line where there is no visible part, before complements
     * it with the previous ones.
     *
     * @param array|string[] $inputLines List of code lines
     * @return string
     */
    private function getAffectedCodeAsString(array $inputLines): string
    {
        $result = '';
        $i      = 0;

        while (\count($inputLines) && ++$i) {
            $line   = \array_pop($inputLines);
            $result = $line . ($i > 1 ? "\n" . $result : '');

            if (\trim($line)) {
                break;
            }
        }

        return $result;
    }

    /**
     * @param string $input
     * @param int $bytesOffset
     * @return string
     */
    public function suffix(string $input, int $bytesOffset): string
    {
        $info = $this->getErrorPositionByOffset($input, $bytesOffset);

        $message  = ' on line ' . $info['line'] . ' at column ' . $info['column'] . "\n";
        $message .= '"' . $info['code'] . '"' . "\n";

        return $message . ' ' . $info['highlight'];
    }

    /**
     * The method draws the highlight of the error place.
     *
     * @param int $charsOffset Error offset in symbols
     * @return string
     */
    private function getStringHighlighting(int $charsOffset): string
    {
        $prefix = '';

        if ($charsOffset > 0) {
            $prefix = \str_repeat(' ', $charsOffset);
        }

        return $prefix . '↑';
    }

    /**
     * Returns the error location in UTF characters by the offset in bytes.
     *
     * @param string $line The code line from which we get a offset in the characters
     * @param int $bytesOffset Length of offset in bytes
     * @return int
     */
    private function getMbColumnPosition(string $line, int $bytesOffset): int
    {
        $slice = \substr($line, 0, $bytesOffset);

        return \mb_strlen($slice, 'UTF-8');
    }

    /**
     * Returns information about the error location: line, column and affected text lines.
     *
     * @param string $input The source code in which we search for a line and a column
     * @param int $bytesOffset Offset in bytes relative to the beginning of the source code
     * @return array
     */
    private function getErrorInfo(string $input, int $bytesOffset): array
    {
        $result = [
            'line'   => 1,
            'column' => 0,
            'trace'  => [],
        ];

        $current = 0;

        foreach (\explode("\n", $input) as $line => $code) {
            $previous = $current;
            $current += \strlen($code) + 1;
            $result['trace'][] = $code;

            if ($current > $bytesOffset) {
                return [
                    'line'   => $line + 1,
                    'column' => $bytesOffset - $previous,
                    'trace'  => $result['trace'],
                ];
            }
        }

        return $result;
    }
}
