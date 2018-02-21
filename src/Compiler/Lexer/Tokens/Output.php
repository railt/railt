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
    public const I_TOKEN_NAME    = 0x00;
    public const I_TOKEN_BODY    = 0x01;
    public const I_TOKEN_LENGTH  = 0x02;
    public const I_TOKEN_OFFSET  = 0x03;
    public const I_TOKEN_CONTEXT = 0x04;
    public const I_TOKEN_CHANNEL = 0x05;
    /**@#-*/
}
