<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer\Driver;

use Parle\Lexer as Parle;
use Parle\LexerException;
use Parle\Token as InternalToken;
use Railt\Io\Readable;
use Railt\Lexer\Definition\TokenDefinition;
use Railt\Lexer\Exception\BadLexemeException;
use Railt\Lexer\LexerInterface;
use Railt\Lexer\Token\EndOfInput;
use Railt\Lexer\Token\Token;
use Railt\Lexer\Token\Unknown;
use Railt\Lexer\TokenInterface;

/**
 * Class ParleStateless
 */
class ParleLexer extends SimpleLexer
{
    /**
     * @var array|string[]
     */
    private $map = [];

    /**
     * @var int
     */
    private $id = 1;

    /**
     * @var Parle
     */
    private $lexer;

    /**
     * ParleStateless constructor.
     *
     * @param array $tokens
     * @param array $skip
     * @throws BadLexemeException
     */
    public function __construct(array $tokens = [], array $skip = [])
    {
        \assert(\class_exists(Parle::class, false));

        $this->lexer = new Parle();
        $this->skipped = $skip;

        foreach ($tokens as $name => $pcre) {
            $this->add($name, $pcre);
        }
    }

    /**
     * @param string $name
     * @param string $pcre
     * @return LexerInterface
     * @throws BadLexemeException
     */
    public function add(string $name, string $pcre): LexerInterface
    {
        try {
            $this->lexer->push($pcre, $this->id);

            $this->map[$this->id] = $name;
            $this->tokens[$this->id] = $pcre;
        } catch (LexerException $e) {
            $message = \preg_replace('/rule\h+id\h+\d+/iu', 'token ' . $name, $e->getMessage());

            throw new BadLexemeException($message);
        }

        ++$this->id;

        return $this;
    }

    /**
     * @param Readable $input
     * @return \Traversable|TokenInterface[]
     */
    protected function exec(Readable $input): \Traversable
    {
        $iterator = $this->getInnerIterator($input);

        while ($iterator->valid()) {
            /** @var InternalToken $current */
            $current = $iterator->current();

            /** @var TokenInterface $token */
            yield $current->id === InternalToken::UNKNOWN
                ? $this->unknown($iterator)
                : $this->token($iterator);
        }

        yield new EndOfInput($this->lexer->marker);
    }

    /**
     * @param Readable $input
     * @return \Generator|\Traversable
     */
    protected function getInnerIterator(Readable $input): \Traversable
    {
        $this->lexer->build();
        $this->lexer->consume($input->getContents());

        $this->lexer->advance();
        $token = $this->lexer->getToken();

        while ($token->id !== InternalToken::EOI) {
            yield $token->id => $token;

            $this->lexer->advance();
            $token = $this->lexer->getToken();
        }
    }

    /**
     * @param \Traversable|\Generator $iterator
     * @return Unknown
     */
    private function unknown(\Traversable $iterator): TokenInterface
    {
        /** @var InternalToken $current */
        $current = $iterator->current();
        $offset = $this->lexer->marker;
        $body = '';

        while ($current->id === InternalToken::UNKNOWN) {
            $body .= $current->value;
            $iterator->next();
            $current = $iterator->current();
        }

        return new Unknown($body, $offset);
    }

    /**
     * @param \Traversable|\Iterator $iterator
     * @return Token
     */
    private function token(\Traversable $iterator): TokenInterface
    {
        /** @var InternalToken $current */
        $current = $iterator->current();

        $iterator->next();

        return new Token($this->map[$current->id], $current->value, $this->lexer->marker);
    }

    /**
     * @return iterable|TokenDefinition[]
     */
    public function getTokenDefinitions(): iterable
    {
        foreach ($this->tokens as $id => $pcre) {
            $name = $this->map[$id];

            yield new TokenDefinition($name, $pcre, ! \in_array($name, $this->skipped, true));
        }
    }
}
