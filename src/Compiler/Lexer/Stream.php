<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Railt\Compiler\Iterator\FilterIterator;
use Railt\Compiler\Iterator\RegexIterator;
use Railt\Compiler\Lexer\Exceptions\UnrecognizedTokenException;
use Railt\Compiler\TokenInterface;
use Railt\Io\Readable;

/**
 * Class Context
 */
class Stream implements \IteratorAggregate
{
    /**
     * @var Readable
     */
    private $readable;

    /**
     * @var int
     */
    private $bytesOffset = 0;

    /**
     * @var int
     */
    private $charsOffset = 0;

    /**
     * @var Runtime
     */
    private $runtime;

    /**
     * Context constructor.
     * @param Readable $readable
     * @param Runtime $runtime
     */
    public function __construct(Readable $readable, Runtime $runtime)
    {
        $this->readable = $readable;
        $this->runtime  = $runtime;
    }

    /**
     * @return \Traversable|FilterIterator|TokenInterface[]
     * @throws \Railt\Compiler\Lexer\Exceptions\LexerException
     */
    public function getIterator(): \Traversable
    {
        return $this->all()->except(function (Token $token): bool {
            return $token->isSkipped();
        });
    }

    /**
     * @return \Traversable|FilterIterator|TokenInterface[]
     */
    public function all(): \Traversable
    {
        return new FilterIterator($this->parse());
    }

    /**
     * @return \Traversable
     * @throws \Railt\Compiler\Lexer\Exceptions\LexerException
     */
    private function parse(): \Traversable
    {
        $body = $this->readable->getContents();

        $iterator = new RegexIterator($this->runtime->pattern(), $body);
        $iterator->setMode(RegexIterator::MODE_STRICT_NAMED_GROUPS);

        foreach ($iterator as $group => $result) {
            $id = Regex::id($group);

            [$body, $context] = [\array_shift($result), $result];

            $this->verifyOffset($body);

            $token = (new Token($this->runtime->name($id), $body))
                ->in($this->runtime->channel($id))
                ->at($this->bytesOffset)
                ->with($context);

            $this->bytesOffset += $token->bytes();
            $this->charsOffset += $token->length();

            yield $token;
        }
    }

    /**
     * @param string $value
     * @throws \Railt\Compiler\Lexer\Exceptions\LexerException
     */
    private function verifyOffset(string $value): void
    {
        $content = $this->readable->getContents();

        if (\substr($content, $this->bytesOffset, \strlen($value)) !== $value) {
            $this->throwUnrecognizedToken($content, $this->bytesOffset);
        }
    }

    /**
     * @param string $content
     * @param int $offset
     * @throws UnrecognizedTokenException
     */
    protected function throwUnrecognizedToken(string $content, int $offset): void
    {
        $errorAtChar = $this->inline(\mb_substr($content, $this->charsOffset, 1));

        $position = $this->readable->getPosition($offset);

        $error = \vsprintf('Unrecognized token "%s" on line %d at column %d', [
            $errorAtChar,
            $position->getLine(),
            $position->getColumn() + 1,
        ]);

        throw UnrecognizedTokenException::fromFile($error, $this->readable, $offset);
    }

    /**
     * @param string $text
     * @return string
     */
    private function inline(string $text): string
    {
        return \str_replace(["\n", "\r"], '', $text);
    }
}
