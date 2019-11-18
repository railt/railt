<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Value;

use Phplrt\Lexer\Lexer;
use Phplrt\Lexer\Token\Composite;
use Phplrt\Contracts\Lexer\LexerInterface;
use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Contracts\Lexer\Exception\LexerExceptionInterface;
use Phplrt\Contracts\Lexer\Exception\LexerRuntimeExceptionInterface;

/**
 * Class Encoder
 */
class Encoder
{
    /**
     * @var string[]
     */
    protected const LEXEMES = [
        self::T_ESCAPED_BACK_SLASH => '\\\\\\\\',
        self::T_UNICODE_CHAR       => '\\\\u([0-9A-Fa-f]{4})',
        self::T_NEW_LINE           => '\\\\n',
        self::T_BACKSPACE          => '\\\\b',
        self::T_FORM_FEED          => '\\\\f',
        self::T_CARRIAGE_RETURN    => '\\\\r',
        self::T_HORIZONTAL_TAB     => '\\\\t',
        self::T_ESCAPED_QUOTE      => '\\\\"',
        self::T_BACK_SLASH         => '\\\\',
        self::T_FORWARD_SLASH      => '/',
        self::T_TEXT               => '[^\\\\]+',
    ];

    /**
     * @var string[]
     */
    protected const SPECIAL_CHARS = [
        self::T_BACKSPACE          => "\u{0008}",  /* '\b' */
        self::T_FORM_FEED          => "\u{000C}",  /* '\f' */
        self::T_NEW_LINE           => "\u{000A}",  /* '\n' */
        self::T_CARRIAGE_RETURN    => "\u{000D}",  /* '\r' */
        self::T_HORIZONTAL_TAB     => "\u{0009}",  /* '\t' */
        self::T_ESCAPED_QUOTE      => '"',         /* '\"' */
        self::T_ESCAPED_BACK_SLASH => '\\',        /* '\\' */
    ];

    /**
     * An escaped unicode character sequence.
     *
     * @see https://graphql.github.io/graphql-spec/draft/#EscapedUnicode
     * @var string
     */
    private const T_UNICODE_CHAR = 'T_UNICODE_CHAR';

    /**
     * A "line feed" character (U+000A)
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-String-Value.Semantics
     * @var string
     */
    private const T_NEW_LINE = 'T_NEW_LINE';

    /**
     * A "backspace" character (U+0000008)
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-String-Value.Semantics
     * @var string
     */
    private const T_BACKSPACE = 'T_BACKSPACE';

    /**
     * A "form feed" character (U+000C)
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-String-Value.Semantics
     * @var string
     */
    private const T_FORM_FEED = 'T_FORM_FEED';

    /**
     * A "carriage return" character (U+000D)
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-String-Value.Semantics
     * @var string
     */
    private const T_CARRIAGE_RETURN = 'T_CARRIAGE_RETURN';

    /**
     * A "horizontal tab" character (U+0009D)
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-String-Value.Semantics
     * @var string
     */
    private const T_HORIZONTAL_TAB = 'T_HORIZONTAL_TAB';

    /**
     * A "back slash" character (reverse solidus: U+005C)
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-String-Value.Semantics
     * @var string
     */
    private const T_BACK_SLASH = 'T_BACK_SLASH';

    /**
     * A "forward slash" character (solidus: U+002FC)
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-String-Value.Semantics
     * @var string
     */
    private const T_FORWARD_SLASH = 'T_FORWARD_SLASH';

    /**
     * An escaped double quote character (U+005C U+0022)
     *
     * @var string
     */
    private const T_ESCAPED_QUOTE = 'T_ESCAPED_QUOTE';

    /**
     * An escaped back slash character (U+005C U+005C)
     *
     * @var string
     */
    private const T_ESCAPED_BACK_SLASH = 'T_ESCAPED_BACK_SLASH';

    /**
     * Basic text which should not be processed in any way.
     *
     * @var string
     */
    private const T_TEXT = 'T_TEXT';

    /**
     * @var string
     */
    private const INVALID_UNICODE_CHAR = '�';

    /**
     * @var LexerInterface
     */
    private LexerInterface $lexer;

    /**
     * @var Encoder|$this|null
     */
    private static ?self $instance = null;

    /**
     * Encoder constructor.
     */
    private function __construct()
    {
        $this->lexer = new Lexer(self::LEXEMES);
    }

    /**
     * @return Encoder|static
     */
    public static function getInstance(): self
    {
        return self::$instance ?? self::$instance = new static();
    }

    /**
     * @param string $value
     * @return string
     * @throws LexerRuntimeExceptionInterface
     * @throws LexerExceptionInterface
     */
    public function encode(string $value): string
    {
        $result = '';

        foreach ($this->lexer->lex($value) as $token) {
            $result .= $this->map($token);
        }

        return $result;
    }

    /**
     * @param TokenInterface|Composite $token
     * @return string
     */
    protected function map(TokenInterface $token): string
    {
        switch ($token->getName()) {
            case TokenInterface::END_OF_INPUT:
                return '';

            case self::T_UNICODE_CHAR:
                \assert($token instanceof Composite);
                \assert($token[0] !== null);

                return $this->unicode($token[0]->getValue());

            default:
                return self::SPECIAL_CHARS[$token->getName()] ?? $token->getValue();
        }
    }

    /**
     * Method for parsing and decode utf-8 character
     * sequences like "\uXXXX" type.
     *
     * @see http://facebook.github.io/graphql/October2016/#sec-String-Value
     * @param string $char
     * @return string
     */
    private function unicode(string $char): string
    {
        try {
            return \mb_convert_encoding(\pack('H*', $char), 'UTF-8', 'UCS-2BE');
        } catch (\Throwable $e) {
            return self::INVALID_UNICODE_CHAR;
        }
    }
}
