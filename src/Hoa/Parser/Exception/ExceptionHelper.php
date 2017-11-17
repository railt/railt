<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Hoa\Compiler\Exception;

/**
 * Trait ExceptionHelper
 */
trait ExceptionHelper
{
    /**
     * This is an auxiliary method that returns the line number, shift,
     * and line of code by shift relative to the beginning of the file.
     *
     * @param   string $text        The source code
     * @param   int    $bytesOffset Offset in bytes
     * @return  array
     */
    protected static function getErrorPositionByOffset($text, $bytesOffset)
    {
        $result = self::getErrorInfo($text, $bytesOffset);
        $code   = self::getAffectedCodeAsString($result['trace']);

        $column = self::getMbColumnPosition($code, $result['column']);

        return [
            'line'      => $result['line'],
            'code'      => $code,
            'column'    => $column,
            'highlight' => self::getStringHighligher($column),
        ];
    }

    /**
     * Returns the last line with an error. If the error occurred on
     * the line where there is no visible part, before complements
     * it with the previous ones.
     *
     * @param array|string[] $textLines List of code lines
     * @return string
     */
    private static function getAffectedCodeAsString(array $textLines)
    {
        $result = '';
        $i = 0;

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
     * The method draws the highlight of the error place.
     *
     * @param  int $charsOffset Error offset in symbols
     * @return string
     */
    private static function getStringHighligher($charsOffset)
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
     * @param  string $line        The code line from which we get a offset in the characters
     * @param  int    $bytesOffset Length of offset in bytes
     * @return int
     */
    private static function getMbColumnPosition($line, $bytesOffset)
    {
        $slice = \substr($line, 0, $bytesOffset);

        return \mb_strlen($slice, 'UTF-8');
    }

    /**
     * Returns information about the error location: line, column and affected text lines.
     *
     * @param string $text        The source code in which we search for a line and a column
     * @param int    $bytesOffset Offset in bytes relative to the beginning of the source code
     * @return array
     */
    private static function getErrorInfo($text, $bytesOffset)
    {
        $result = [
            'line'   => 1,
            'column' => 0,
            'trace'  => [],
        ];

        $current = 0;

        foreach (\explode("\n", $text) as $line => $code) {
            $previous = $current;
            $current += \strlen($code) + 1;
            $result['trace'][] = $code;

            if ($current > $bytesOffset) {
                return [
                    'line'   => $line + 1,
                    'column' => $bytesOffset - $previous,
                    'trace'  => $result['trace']
                ];
            }
        }

        return $result;
    }
}
