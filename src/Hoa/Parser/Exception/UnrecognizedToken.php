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
 * Class UnrecognizedToken
 */
class UnrecognizedToken extends Exception
{
    use ExceptionHelper;

    /**
     * Column.
     *
     * @var int
     */
    protected $column = 0;

    /**
     * Override line and add column support.
     *
     * @param   string  $message    Formatted message.
     * @param   int     $code       Code (the ID).
     * @param   array   $arg        RaiseError string arguments.
     * @param   int     $line       Line.
     * @param   int     $column     Column.
     */
    public function __construct($message, $code, $arg, $line, $column)
    {
        parent::__construct($message, $code, $arg);

        $this->line   = $line;
        $this->column = $column;
    }

    /**
     * @param   string  $message        Formatted message.
     * @param   string  $text           Source code
     * @param   int     $offsetInBytes  Error offset in bytes
     * @param   int     $code           Code (the ID).
     * @return  static
     */
    public static function fromOffset($message, $text, $offsetInBytes, $code = 0)
    {
        $info     = self::getErrorPositionByOffset($text, $offsetInBytes);

        // Formatted message
        $message .= ' at line %s and column %s' . \PHP_EOL .
            $info['code'] . \PHP_EOL .
            $info['highlight'];

        return new static($message, $code, [$info['line'], $info['column']], $info['line'], $info['column']);
    }

    /**
     * Get column.
     *
     * @return  int
     */
    public function getColumn()
    {
        return $this->column;
    }
}
