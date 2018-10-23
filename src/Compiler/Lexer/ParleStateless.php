<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Parle\Lexer;
use Parle\LexerException;
use Parle\Token as InternalToken;
use Railt\Compiler\Exception\BadLexemeException;
use Railt\Compiler\Exception\UnsupportedLexerRuntimeException;
use Railt\Compiler\Lexer\Result\Eoi;
use Railt\Compiler\Lexer\Result\Token;
use Railt\Compiler\Lexer\Result\Unknown;
use Railt\Compiler\TokenInterface;
use Railt\Io\Readable;

/**
 * Class ParleStateless
 */
class ParleStateless implements Stateless
{
    /**
     * @var array|string[]
     */
    private $map = [];

    /**
     * @var array|string[]
     */
    private $tokens = [];

    /**
     * @var array|string[]
     */
    private $skip = [];

    /**
     * @var int
     */
    private $id = 1;

    /**
     * @var Lexer
     */
    private $lexer;

    /**
     * Parle constructor.
     * @throws UnsupportedLexerRuntimeException
     */
    public function __construct()
    {
        if (! \class_exists(Lexer::class)) {
            throw new UnsupportedLexerRuntimeException('This runtime required parle extension');
        }

        $this->lexer = new Lexer();
    }

    /**
     * @param string $name
     * @param string $pcre
     * @return Stateless
     * @throws \Railt\Compiler\Lexer\Exception\BadLexemeException
     */
    public function add(string $name, string $pcre): Stateless
    {
        try {
            $this->lexer->push($pcre, $this->id);

            $this->map[$this->id]    = $name;
            $this->tokens[$this->id] = $pcre;
        } catch (LexerException $e) {
            $message = \preg_replace('/rule\h+id\h+\d+/iu', 'token ' . $name, $e->getMessage());

            throw new BadLexemeException($message);
        }

        ++$this->id;

        return $this;
    }

    /**
     * @param string $name
     * @return Stateless
     */
    public function skip(string $name): Stateless
    {
        $this->skip[] = $name;

        return $this;
    }

    /**
     * @return iterable
     */
    public function getTokens(): iterable
    {
        foreach ($this->tokens as $id => $pcre) {
            yield $this->map[$id] => $pcre;
        }
    }

    /**
     * @param Readable $input
     * @return \Traversable
     */
    public function lex(Readable $input): \Traversable
    {
        foreach ($this->exec($input) as $token) {
            if (! \in_array($token->name(), $this->skip, true)) {
                yield $token;
            }
        }
    }

    /**
     * @param Readable $input
     * @return \Traversable|TokenInterface[]
     */
    private function exec(Readable $input): \Traversable
    {
        $this->lexer->build();
        $this->lexer->consume($input->getContents());

        $token = $this->next();

        while ($token->id !== InternalToken::EOI) {
            yield $token->id === InternalToken::UNKNOWN
                ? new Unknown($token->value, $this->lexer->marker)
                : new Token($this->map[$token->id], $token->value, $this->lexer->marker);

            $token = $this->next();
        }

        yield new Eoi($this->lexer->marker);
    }

    /**
     * @return InternalToken
     */
    private function next(): InternalToken
    {
        $this->lexer->advance();

        return $this->lexer->getToken();
    }

    /**
     * @return iterable
     */
    public function getIgnoredTokens(): iterable
    {
        return \array_values($this->skip);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return \in_array($name, $this->map, true);
    }
}
