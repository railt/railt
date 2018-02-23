<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer\Tokens;

/**
 * Class Output
 */
final class Output
{
    /**@#+
     * An indexes list of the output array with token information.
     */
    public const T_NAME    = 0x00;
    public const T_VALUE    = 0x01;
    public const T_LENGTH  = 0x02;
    public const T_OFFSET  = 0x03;
    public const T_CONTEXT = 0x04;
    public const T_CHANNEL = 0x05;
    /**@#-*/
}
