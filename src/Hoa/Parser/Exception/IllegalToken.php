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
 * Class IllegalToken
 */
class IllegalToken extends Exception
{
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

        return;
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
