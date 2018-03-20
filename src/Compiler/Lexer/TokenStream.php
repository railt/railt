<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Railt\Compiler\Iterator\RegexNamedGroupsIterator;
use Railt\Compiler\Lexer\Exceptions\UnrecognizedTokenException;
use Railt\Compiler\Lexer\Result\Eof;
use Railt\Compiler\Lexer\Result\Token;
use Railt\Compiler\Lexer\Result\Undefined;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class Stream
 */
class TokenStream implements \IteratorAggregate
{
    /**
     * @var File
     */
    private $input;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $skip;

    /**
     * TokenStream constructor.
     * @param Readable $input
     * @param string $pattern
     * @param array $skip
     */
    public function __construct(Readable $input, string $pattern, array $skip)
    {
        $this->skip = $skip;
        $this->input   = $input;
        $this->body    = $input->getContents();
        $this->pattern = $pattern;
    }

    /**
     * @return \Traversable|\Railt\Compiler\Lexer\TokenInterface[]
     * @throws \Railt\Compiler\Lexer\Exceptions\UnrecognizedTokenException
     * @throws \RuntimeException
     * @throws \Railt\Compiler\Lexer\Exceptions\LexerException
     */
    public function getIterator(): \Traversable
    {
        $stream = $this->stream()->getIterator();

        foreach ($stream as $name => $groups) {
            $token = (new Token($name, $body = \array_shift($groups)))
                ->at($this->offset)
                ->with($groups);

            $this->verifyOffset($body);

            $this->offset += $token->bytes();

            if ($this->skip[$name] ?? false) {
                yield $token;
            }
        }

        yield new Eof($this->offset);
    }

    /**
     * @return \Traversable|RegexNamedGroupsIterator
     */
    private function stream(): \Traversable
    {
        return new RegexNamedGroupsIterator($this->pattern, $this->input->getContents());
    }

    /**
     * @param string $value
     * @throws \Railt\Compiler\Lexer\Exceptions\UnrecognizedTokenException
     */
    private function verifyOffset(string $value): void
    {
        $required = \substr($this->body, $this->offset, \strlen($value));

        if ($required !== $value) {
            $charsOffset = \mb_strlen(\substr($this->body, 0, $this->offset));
            $tokenSlice  = \mb_substr($this->body, $charsOffset, 1);

            $token = (new Undefined($tokenSlice))
                ->at($this->offset)
                ->skip();

            $error = \sprintf('Syntax error, unrecognized token %s', (string)$token);

            throw UnrecognizedTokenException::fromFile($error, $this->input, $this->offset);
        }
    }
}

