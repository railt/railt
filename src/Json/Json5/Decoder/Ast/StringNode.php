<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Decoder\Ast;

use Phplrt\Ast\LeafInterface;
use Phplrt\Io\File;
use Phplrt\Io\Readable;
use Phplrt\Lexer\TokenInterface;
use Railt\Json\Exception\JsonSyntaxException;
use Railt\Json\Json;
use Railt\Json\Json5\Decoder\StringLexer;

/**
 * @internal Internal class for json5 abstract syntax tree node representation
 */
class StringNode implements NodeInterface
{
    /**
     * @var LeafInterface
     */
    private $leaf;

    /**
     * StringNode constructor.
     *
     * @param array $children
     */
    public function __construct(array $children = [])
    {
        $this->leaf = \reset($children);
    }

    /**
     * @return mixed|string
     * @throws \InvalidArgumentException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     * @throws JsonSyntaxException
     */
    public function reduce()
    {
        return $this->parse($this->leaf->getValue(1));
    }

    /**
     * @param string $value
     * @return string
     * @throws JsonSyntaxException
     * @throws \InvalidArgumentException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     */
    private function parse(string $value): string
    {
        [$result, $sources] = ['', File::fromSources($value)];

        foreach (StringLexer::getInstance()->lex($sources) as $token) {
            $result .= $this->render($token, $sources);
        }

        return $result;
    }

    /**
     * @param TokenInterface $token
     * @param Readable $sources
     * @return string
     * @throws JsonSyntaxException
     */
    private function render(TokenInterface $token, Readable $sources): string
    {
        switch ($token->getName()) {
            case 'T_CHAR_UTF':
                return $this->renderUtfChar($token->getValue(1));

            case 'T_CHAR_ALT_UTF':
                $char = $token->getValue(1);
                $char = \str_pad($char, 4, '0', \STR_PAD_LEFT);

                return $this->renderUtfChar($char);

            case 'T_CHAR_NB_NL':
                return '';

            case 'T_CHAR_LF':
                return "\u{000A}";

            case 'T_CHAR_BS':
                return "\u{0008}";

            case 'T_CHAR_FF':
                return "\u{000C}";

            case 'T_CHAR_CR':
                return "\u{000D}";

            case 'T_CHAR_HT':
                return "\u{0009}";

            case 'T_CHAR_VT':
                return "\u{000B}";

            case 'T_CHAR_NULL':
                return "\u{0000}";

            case 'T_ESC_BACKSLASH':
                return '\\';

            case 'T_ESC_SINGLE_QUOTE':
                return "'";

            case 'T_ESC_DOUBLE_QUOTE':
                return '"';

            case 'T_UNESCAPED_CHAR':
                return $token->getValue(1);

            default:
                return $this->unpack($token, $sources);
        }
    }

    /**
     * Method for parsing and decode utf-8 character
     * sequences like "\u0000" and "\x00" type.
     *
     * @see https://www.ecma-international.org/ecma-262/5.1/#sec-7.8.4
     * @see hhttps://spec.json5.org/#strings
     *
     * @param string $code
     * @return string
     */
    private function renderUtfChar(string $code): string
    {
        try {
            return \mb_convert_encoding(\pack('H*', $code), 'UTF-8', 'UCS-2BE');
        } catch (\Error | \ErrorException $error) {
            try {
                return (string)Json::decode('{"char": "\\u' . $code . '"}')['char'];
            } catch (\Throwable $e) {
                return '\u' . $code;
            }
        }
    }

    /**
     * @param TokenInterface $token
     * @param Readable $sources
     * @return string
     * @throws JsonSyntaxException
     */
    private function unpack(TokenInterface $token, Readable $sources): string
    {
        $value = $token->getValue();

        if (\is_int(\strpos($value, "\n"))) {
            $string = \str_replace("\n", '\n', $this->leaf->getValue());

            $pos = $sources->getPosition($token->getOffset() + 1);

            $error = 'Unescaped line break was found on line %d at column %d in %s (%s)';
            $error = \sprintf($error, $pos->getLine(), $pos->getColumn(), $string, $this->leaf->getName());

            throw new JsonSyntaxException($error);
        }

        return $value;
    }
}
