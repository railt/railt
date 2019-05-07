<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Parser;

/**
 * Class Parser
 */
final class Parser extends BaseParser
{
    /**
     * @var string
     */
    public const GRAMMAR_PATHNAME = __DIR__ . '/../Resources/graphql/grammar.pp2';

    /**
     * Make tokens public
     * @var string[]
     */
    public const LEXER_TOKENS = parent::LEXER_TOKENS;

    /**
     * @param string $lexeme
     * @return string
     */
    public static function pattern(string $lexeme): string
    {
        return \sprintf('/^%s$/', self::LEXER_TOKENS[$lexeme]);
    }

    /**
     * @param string $lexeme
     * @param string $value
     * @return bool
     */
    public static function match(string $lexeme, string $value): bool
    {
        return (bool)\preg_match(self::pattern($lexeme), $value);
    }
}
