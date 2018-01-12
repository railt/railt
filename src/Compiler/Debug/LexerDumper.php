<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Debug;

use Railt\Compiler\Lexer;
use Railt\Compiler\Parser;

/**
 * Class LexerDumper
 */
class LexerDumper implements Dumper
{
    use DumpHelpers;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    private $sources;

    /**
     * @var int
     */
    private $offsetWidth = 6;

    /**
     * @var int
     */
    private $keepWidth = 4;


    /**
     * @var int
     */
    private $tokenWidth = 25;

    /**
     * @var int
     */
    private $scopeWidth = 10;

    /**
     * @var int
     */
    private $width = 120;

    /**
     * @var int
     */
    private $lengthWidth = 10;

    /**
     * LexerDumper constructor.
     * @param Parser $parser
     * @param string $sources
     */
    public function __construct(Parser $parser, string $sources)
    {
        $this->parser  = $parser;
        $this->sources = $sources;
    }

    /**
     * @param int $width
     * @return LexerDumper
     */
    public function resize(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function toString(): string
    {
        $reflection = new \ReflectionObject($this->parser);
        $method     = $reflection->getMethod('getLexer');
        $method->setAccessible(true);

        /** @var Lexer $lexer */
        $lexer = $method->invoke($this->parser, $this->sources);
        $lexer->keepAll();

        $header =
            $this->delimiter() .
            $this->header() .
            $this->delimiter();

        $result =
            $this->delimiter('=') .
            $this->header() .
            $this->delimiter('=');

        foreach ($lexer as $i => $token) {
            if ($i !== 0 && ($i % 20) === 0) {
                $result .= $header;
            }

            $length = \strlen($token[Lexer\Token::T_VALUE]) . ' (' . \mb_strlen($token[Lexer\Token::T_VALUE]) . ')';

            $result .= \vsprintf($this->template(), [
                $token[Lexer\Token::T_OFFSET] ?? 0,
                $token[Lexer\Token::T_KEEP] ? '+' : '-',
                $this->trim($this->inline($token[Lexer\Token::T_NAMESPACE]), $this->scopeWidth - 2),
                $this->trim($this->inline($token[Lexer\Token::T_TOKEN]), $this->tokenWidth - 2),
                $this->trim($this->inline($token[Lexer\Token::T_VALUE]), $this->valueWidth() - 2),
                $this->trim($length, $this->lengthWidth - 2),
            ]);
        }

        return $result . $this->delimiter('=');
    }

    /**
     * @param string $line
     * @param int $width
     * @return string
     */
    private function trim(string $line, int $width): string
    {
        return \mb_strlen($line) > $width
            ? \mb_substr($line, 0, $width - 2) . '...'
            : $line;
    }

    /**
     * @param string $input
     * @return string
     */
    private function delimiter(string $input = '-'): string
    {
        return '|' . \str_repeat($input, $this->width - 2) . '|' .
            \PHP_EOL;
    }

    /**
     * @return string
     */
    private function header(): string
    {
        return \sprintf($this->template(), 'Offset', 'Keep', 'Scope', 'Token', 'Length', 'Value');
    }

    /**
     * @return string
     */
    private function template(): string
    {
        $valueWidth = $this->valueWidth();

        return '| %' . $this->offsetWidth . 's ' .
            '| %-' . $this->keepWidth . 's ' .
            '| %-' . $this->scopeWidth . 's ' .
            '| %-' . $this->tokenWidth . 's ' .
            '| %-' . $valueWidth . 's ' .
            '| %-' . $this->lengthWidth . 's |' .
            \PHP_EOL;
    }

    /**
     * @return int
     */
    private function valueWidth(): int
    {
        $columns = 6;

        // "| ... "
        //  ^^   ^ = 3
        $prefix = 3;

        // "... |"
        //      ^ = 1
        $suffix = 1;

        return $this->width -
            $this->offsetWidth -
            $this->keepWidth -
            $this->scopeWidth -
            $this->lengthWidth -
            $this->tokenWidth -
            ($columns * $prefix + $suffix);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            return $this->toString();
        } catch (\Throwable $e) {
            return (string)$e;
        }
    }
}
