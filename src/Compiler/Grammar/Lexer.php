<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar;

use Railt\Lexer\Configuration;
use Railt\Lexer\Lexer as BaseLexer;
use Railt\Lexer\Tokens\Channel;

/**
 * Class Lexer
 */
class Lexer extends BaseLexer
{
    /**@#+
     * List of channels with tokens.
     */
    public const CHANNEL_TOKENS  = Channel::DEFAULT;
    public const CHANNEL_SKIPPED = Channel::SKIPPED;
    public const CHANNEL_COMMENT = Channel::SKIPPED;
    /**@#-*/


    /**@#+
     * List of tokens used inside grammar files:
     *  1) All tokens in the range {0x00 ... 0x0f} are used for hidden or
     *     ignored data that do not participate in the parsing of the semantic
     *     analyzer (Reader).
     *  2) All tokens in the range {0x10 ... 0x1f} are used for declarations
     *     of types of grammar (tokens, pragmas, rules, etc.).
     *  3) All tokens in the range {0x20 ... 0x3f} are used for grammar
     *     rules tokens.
     *  4) All tokens that begin with 0x40 are reserved. This set is used
     *     in the event that the selected sections will not be enough.
     */
    public const T_WHITESPACE      = 0x00;
    public const T_COMMENT         = 0x01;
    public const T_BLOCK_COMMENT   = 0x02;
    public const T_PRAGMA          = 0x10;
    public const T_TOKEN           = 0x11;
    public const T_INCLUDE         = 0x12;
    public const T_NODE_DEFINITION = 0x13;
    public const T_OR              = 0x21;
    public const T_ZERO_OR_ONE     = 0x22;
    public const T_ONE_OR_MORE     = 0x23;
    public const T_ZERO_OR_MORE    = 0x24;
    public const T_N_TO_M          = 0x25;
    public const T_ZERO_TO_M       = 0x26;
    public const T_N_OR_MORE       = 0x27;
    public const T_EXACTLY_N       = 0x28;
    public const T_SKIPPED         = 0x29;
    public const T_KEPT            = 0x2a;
    public const T_NAMED           = 0x2b;
    public const T_NODE            = 0x2c;
    public const T_GROUP_OPEN      = 0x2d;
    public const T_GROUP_CLOSE     = 0x2e;
    /**#@-*/

    /**
     * @var array|string[] Tokens list
     */
    private const TOKENS_LIST = [
        self::T_WHITESPACE      => ['\s+', self::CHANNEL_SKIPPED],
        self::T_COMMENT         => ['//[^\\n]*', self::CHANNEL_COMMENT],
        self::T_BLOCK_COMMENT   => ['/\\*.*?\\*/', self::CHANNEL_COMMENT],
        self::T_PRAGMA          => '%pragma\h+([\w\.]+)\h+(.+?)\s+',
        self::T_TOKEN           => '%token\h+(\w+)\h+(.+?)(?:\h+\->\h+(\w+))?\s+',
        self::T_INCLUDE         => '%include\h+(.+?)\s+',
        self::T_NODE_DEFINITION => '#?\w+\s*:',
        self::T_OR              => '\\|',
        self::T_ZERO_OR_ONE     => '\\?',
        self::T_ONE_OR_MORE     => '\\+',
        self::T_ZERO_OR_MORE    => '\\*',
        self::T_N_TO_M          => '{\h*(\d+),\h*(\d+)\h*}',
        self::T_ZERO_TO_M       => '{\h*,\h*(\d+)\h*}',
        self::T_N_OR_MORE       => '{\h*(\d+)\h*,\h*}',
        self::T_EXACTLY_N       => '{(\d+)}',
        self::T_SKIPPED         => '::(\w+)::',
        self::T_KEPT            => '<(\w+)>',
        self::T_NAMED           => '(\w+)\\(\\)',
        self::T_NODE            => '#(\w+)',
        self::T_GROUP_OPEN      => '\\(',
        self::T_GROUP_CLOSE     => '\\)',
    ];

    /**
     * Lexer constructor.
     */
    public function __construct()
    {
        parent::__construct(self::TOKENS_LIST, $this->configs());
    }

    /**
     * @return Configuration
     */
    private function configs(): Configuration
    {
        return Configuration::new([
            'modeDotAll' => true,
        ]);
    }

    /**
     * @param string|int $id
     * @return string
     */
    public static function getTokenName($id): string
    {
        static $constants;

        if ($constants === null) {
            try {
                $constants = (new \ReflectionClass(self::class))->getConstants();
                $constants = \array_flip(\array_filter($constants, '\\is_int'));
            } catch (\ReflectionException $e) {
                return (string)$id;
            }
        }

        return $constants[$id] ?? (string)$id;
    }
}
