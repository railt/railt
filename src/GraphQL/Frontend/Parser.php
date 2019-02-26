<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Frontend;

/**
 * Class Parser
 */
final class Parser extends BaseParser
{
    /**
     * @param string $token
     * @param string $value
     * @return bool
     */
    public static function match(string $token, string $value): bool
    {
        return (bool)\preg_match(static::pattern($token), $value);
    }

    /**
     * @param string $token
     * @return string
     */
    public static function pattern(string $token): string
    {
        return \sprintf('/^%s$/', static::LEXER_TOKENS[$token]);
    }
}
