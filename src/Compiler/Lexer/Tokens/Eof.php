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
 * Class Eof
 */
final class Eof
{
    /**
     * End of file token name
     */
    public const T_NAME = 'EOF';

    /**
     * @param int $offset
     * @return array
     */
    public static function create(int $offset): array
    {
        return [
            Output::I_TOKEN_NAME    => self::T_NAME,
            Output::I_TOKEN_BODY    => "\0",
            Output::I_TOKEN_LENGTH  => 0,
            Output::I_TOKEN_OFFSET  => $offset,
            Output::I_TOKEN_CONTEXT => [],
            Output::I_TOKEN_CHANNEL => Channel::SYSTEM,
        ];
    }
}
