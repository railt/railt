<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

/**
 * Class Pragma
 */
final class Pragma
{
    /**
     * Launch lexical analysis with Unicode support.
     */
    public const LEXER_UNICODE = 'lexer.unicode';

    /**
     * Lookahead parsing depth.
     */
    public const LOOKAHEAD_DEPTH = 'parser.lookahead';

    /**
     * @param array $pragmas
     * @return bool
     */
    public static function isUnicode(array $pragmas): bool
    {
        return \array_key_exists(self::LEXER_UNICODE, $pragmas) &&
            $pragmas[self::LEXER_UNICODE] === true;
    }

    /**
     * @param array $pragmas
     * @return int
     */
    public static function getLookahead(array $pragmas): int
    {
        if (\array_key_exists(self::LOOKAHEAD_DEPTH, $pragmas)) {
            return \max(0, (int)$pragmas[self::LOOKAHEAD_DEPTH]);
        }

        return 1024;
    }
}
