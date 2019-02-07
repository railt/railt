<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Decoder;

use Railt\Io\Readable;
use Railt\Lexer\Factory;
use Railt\Lexer\LexerInterface;
use Railt\Lexer\TokenInterface;

/**
 * Class StringLexer
 */
class StringLexer implements LexerInterface
{
    /**
     * @var string[]
     */
    protected const INNER_TOKENS = [
        'T_CHAR_NB_NL'       => '\\\\\\n',
        'T_CHAR_LF'          => '\\\\n',
        'T_CHAR_BS'          => '\\\\b',
        'T_CHAR_FF'          => '\\\\f',
        'T_CHAR_CR'          => '\\\\r',
        'T_CHAR_HT'          => '\\\\t',
        'T_CHAR_VT'          => '\\\\v',
        'T_CHAR_NULL'        => '\\\\0',
        'T_ESC_SINGLE_QUOTE' => '\\\\\'',
        'T_ESC_DOUBLE_QUOTE' => '\\\\"',
        'T_ESC_BACKSLASH'    => '\\\\\\\\',
        'T_CHAR_UTF'         => '\\\\u([0-9a-fA-Z]{4})',
        'T_CHAR_ALT_UTF'     => '\\\\x([0-9a-fA-Z]{1,2})',
        'T_UNESCAPED_CHAR'   => '\\\\(\\w)',
    ];

    /**
     * @var StringLexer|self
     */
    private static $instance;

    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * StringLexer constructor.
     *
     * @throws \InvalidArgumentException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     */
    public function __construct()
    {
        $this->lexer = Factory::create(static::INNER_TOKENS, ['T_EOI']);
    }

    /**
     * @return StringLexer|LexerInterface
     * @throws \InvalidArgumentException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     */
    public static function getInstance(): LexerInterface
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param Readable $input
     * @return \Traversable|TokenInterface[]
     */
    public function lex(Readable $input): \Traversable
    {
        return $this->lexer->lex($input);
    }

    /**
     * @return iterable
     */
    public function getTokenDefinitions(): iterable
    {
        return static::INNER_TOKENS;
    }
}
