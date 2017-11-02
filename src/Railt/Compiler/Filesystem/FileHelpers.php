<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Filesystem;

/**
 * Trait FileHelpers
 */
trait FileHelpers
{
    /**
     * Returns information about the error location: line, column and affected text lines.
     *
     * @param string $sources The source code in which we search for a line and a column
     * @param int $bytesOffset Offset in bytes relative to the beginning of the source code
     * @return array
     */
    public static function getErrorInfo(string $sources, int $bytesOffset): array
    {
        $trace  = [];
        $result = [
            'line'   => 1,
            'column' => 0,
            'code'   => '',
        ];

        $current = 0;

        foreach (\explode("\n", $sources) as $line => $code) {
            $previous = $current;
            $current  += \strlen($code) + 1;
            $trace[]  = $code;

            if ($current > $bytesOffset) {
                return [
                    'line'   => $line + 1,
                    'column' => $bytesOffset - $previous,
                    'code'   => static::getAffectedCodeAsString($trace),
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
     * @param array|string[] $textLines List of code lines
     * @return string
     */
    public static function getAffectedCodeAsString(array $textLines): string
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
}
