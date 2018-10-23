<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Lexer;

use Railt\Compiler\Generator\LexerGenerator;
use Railt\Compiler\Lexer;
use Railt\Compiler\LexerInterface;
use Railt\Io\Readable;

/**
 * Class Runtime
 */
abstract class Runtime
{
    private const CLASS_NAME = 'Grammar';

    /**@#+
     * List of tokens used inside grammar files.
     */
    public const T_WHITESPACE      = 'T_WHITESPACE';
    public const T_COMMENT         = 'T_COMMENT';
    public const T_BLOCK_COMMENT   = 'T_BLOCK_COMMENT';
    public const T_PRAGMA          = 'T_PRAGMA';
    public const T_TOKEN           = 'T_TOKEN';
    public const T_SKIP            = 'T_SKIP';
    public const T_INCLUDE         = 'T_INCLUDE';
    public const T_NODE_DEFINITION = 'T_NODE_DEFINITION';
    public const T_OR              = 'T_OR';
    public const T_ZERO_OR_ONE     = 'T_ZERO_OR_ONE';
    public const T_ONE_OR_MORE     = 'T_ONE_OR_MORE';
    public const T_ZERO_OR_MORE    = 'T_ZERO_OR_MORE';
    public const T_N_TO_M          = 'T_N_TO_M';
    public const T_ZERO_TO_M       = 'T_ZERO_TO_M';
    public const T_N_OR_MORE       = 'T_N_OR_MORE';
    public const T_EXACTLY_N       = 'T_EXACTLY_N';
    public const T_SKIPPED         = 'T_SKIPPED';
    public const T_KEPT            = 'T_KEPT';
    public const T_NAMED           = 'T_NAMED';
    public const T_NODE            = 'T_NODE';
    public const T_GROUP_OPEN      = 'T_GROUP_OPEN';
    public const T_GROUP_CLOSE     = 'T_GROUP_CLOSE';
    /**#@-*/

    /**
     * @var array|string[] Tokens list
     */
    private const TOKENS_LIST = [
        self::T_WHITESPACE      => '\s+',
        self::T_COMMENT         => '//[^\\n]*',
        self::T_BLOCK_COMMENT   => '/\\*.*?\\*/',
        self::T_PRAGMA          => '%pragma\h+([\w\.]+)\h+(.+?)\s+',
        self::T_TOKEN           => '%token\h+(\w+)\h+(.+?)(?:\h+\->\h+(\w+))?\s+',
        self::T_SKIP            => '%skip\h+(\w+)\h+(.+?)\s+',
        self::T_INCLUDE         => '%include\h+(.+?)\s+',
        self::T_NODE_DEFINITION => '(#?\w+)\s*:',
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
     *  A list of token contexts
     */
    private const TOKEN_CONTEXTS = [
        // self::T_INCLUDE         => 1,
        // self::T_NODE_DEFINITION => 1,
        // self::T_SKIPPED         => 1,
        // self::T_KEPT            => 1,
        // self::T_NAMED           => 1,
        // self::T_NODE            => 1,
    ];

    /**
     * A list of skipped tokens
     */
    private const TOKENS_SKIP = [
        self::T_WHITESPACE,
        self::T_COMMENT,
        self::T_BLOCK_COMMENT,
    ];

    /**
     * @return Readable
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \RuntimeException
     */
    public static function build(): Readable
    {
        $generator = new LexerGenerator(static::new());
        $generator->namespace(__NAMESPACE__);
        $generator->class(self::CLASS_NAME);

        return $generator->build()->saveTo(__DIR__);
    }

    /**
     * @return LexerInterface|Lexer
     */
    public static function new(): LexerInterface
    {
        return new Lexer(static::getTokenDefinitions());
    }

    /**
     * @return iterable
     */
    public static function getTokenDefinitions(): iterable
    {
        return self::TOKENS_LIST;
    }

    /**
     * @return iterable
     */
    public static function getSkippedTokens(): iterable
    {
        return self::TOKENS_SKIP;
    }

    /**
     * @return iterable
     */
    public static function getTokenContexts(): iterable
    {
        return self::TOKEN_CONTEXTS;
    }
}
