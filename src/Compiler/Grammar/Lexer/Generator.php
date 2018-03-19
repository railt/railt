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
use Railt\Compiler\Lexer\Definition;
use Railt\Compiler\Lexer\Lexer;
use Railt\Compiler\LexerInterface;
use Railt\Io\Readable;

/**
 * Class Generator
 */
abstract class Generator extends GrammarToken
{
    private const CLASS_NAME = 'GrammarLexer';

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
     * A list of skipped tokens
     */
    private const TOKENS_SKIP = [
        self::T_WHITESPACE,
        self::T_COMMENT,
        self::T_BLOCK_COMMENT,
    ];

    /**
     * @return LexerInterface
     * @throws \Railt\Io\Exceptions\NotReadableException
     * @throws \RuntimeException
     */
    public static function fresh(): LexerInterface
    {
        static::build();

        $class = __NAMESPACE__ . '\\' . self::CLASS_NAME;

        return new $class();
    }

    /**
     * @return Readable
     * @throws \Railt\Io\Exceptions\NotReadableException
     * @throws \RuntimeException
     */
    public static function build(): Readable
    {
        $runtime = new Lexer(\iterator_to_array(static::getTokenDefinitions()));
        $runtime->dotAll(true);

        $generator = new LexerGenerator($runtime);
        $generator->namespace(__NAMESPACE__);
        $generator->class(self::CLASS_NAME);

        return $generator->build()->saveTo(__DIR__);
    }

    /**
     * @return \Traversable|GrammarToken[]|iterable
     */
    public static function getTokenDefinitions(): \Traversable
    {
        foreach (self::TOKENS_LIST as $id => $value) {
            $token = new Definition($id, $value);

            if (\in_array($id, self::TOKENS_SKIP, true)) {
                $token->in(Definition::CHANNEL_SKIP);
            }

            yield $token;
        }
    }
}
