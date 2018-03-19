<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Lexer;

/**
 * Class GrammarToken
 */
class GrammarToken
{
    /**@#+
     * List of tokens used inside grammar files:
     *  1) All tokens in the range {0x10 ... 0x1f} are used for hidden or
     *     ignored data that do not participate in the parsing of the semantic
     *     analyzer (Reader).
     *  2) All tokens in the range {0x20 ... 0x2f} are used for declarations
     *     of types of grammar (tokens, pragmas, rules, etc.).
     *  3) All tokens in the range {0x30 ... 0x4f} are used for grammar
     *     rules tokens.
     *  4) All tokens that begin with 0x50 are reserved. This set is used
     *     in the event that the selected sections will not be enough.
     */
    public const T_WHITESPACE      = 0x11;
    public const T_COMMENT         = 0x12;
    public const T_BLOCK_COMMENT   = 0x13;
    public const T_PRAGMA          = 0x20;
    public const T_TOKEN           = 0x21;
    public const T_SKIP            = 0x22;
    public const T_INCLUDE         = 0x23;
    public const T_NODE_DEFINITION = 0x24;
    public const T_OR              = 0x31;
    public const T_ZERO_OR_ONE     = 0x32;
    public const T_ONE_OR_MORE     = 0x33;
    public const T_ZERO_OR_MORE    = 0x34;
    public const T_N_TO_M          = 0x35;
    public const T_ZERO_TO_M       = 0x36;
    public const T_N_OR_MORE       = 0x37;
    public const T_EXACTLY_N       = 0x38;
    public const T_SKIPPED         = 0x39;
    public const T_KEPT            = 0x3a;
    public const T_NAMED           = 0x3b;
    public const T_NODE            = 0x3c;
    public const T_GROUP_OPEN      = 0x3d;
    public const T_GROUP_CLOSE     = 0x3e;
    /**#@-*/

    /**
     * @param int $id
     * @return string
     */
    public static function getName(int $id): string
    {
        static $tokens;

        if ($tokens === null) {
            $tokens = [];

            try {
                $constants = (new \ReflectionClass(static::class))
                    ->getConstants();
            } catch (\ReflectionException $e) {
                $constants = [];
            }

            foreach ($constants as $name => $value) {
                if (\is_int($value)) {
                    $tokens[$value] = $name;
                }
            }
        }

        return $tokens[$id];
    }
}
