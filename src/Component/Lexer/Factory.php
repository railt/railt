<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Lexer;

use Railt\Component\Lexer\Driver\NativeRegex;
use Railt\Component\Lexer\Driver\ParleLexer;

/**
 * Class Lexer
 */
class Factory
{
    /**
     * The factory should return any implementation supporting the PCRE lookahead syntax.
     *
     * @var int
     */
    public const LOOKAHEAD = 0x02;

    /**
     * The factory should return any multistate implementation.
     *
     * @var int
     */
    public const MULTISTATE = 0x04;

    /**
     * @param array $tokens
     * @param array $skip
     * @param int $flags
     * @return LexerInterface|SimpleLexerInterface|MultistateLexerInterface
     * @throws Exception\BadLexemeException
     * @throws \InvalidArgumentException
     */
    public static function create(array $tokens, array $skip = [], int $flags = self::LOOKAHEAD): LexerInterface
    {
        switch (true) {
            case self::isMultistate($flags):
                $error = \vsprintf('Multistate %slexer does not implemented yet', [
                    self::isLookahead($flags) ? 'lookahead ' : '',
                ]);
                throw new \InvalidArgumentException($error);

            case ! self::isLookahead($flags) && \class_exists(\Parle\Lexer::class, false):
                return new ParleLexer($tokens, $skip);

            default:
                return new NativeRegex($tokens, $skip);
        }
    }

    /**
     * @param int $flags
     * @return bool
     */
    private static function isLookahead(int $flags): bool
    {
        return (bool)($flags & self::LOOKAHEAD);
    }

    /**
     * @param int $flags
     * @return bool
     */
    private static function isMultistate(int $flags): bool
    {
        return (bool)($flags & self::MULTISTATE);
    }
}
