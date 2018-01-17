<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Railt\Compiler\Exception\InvalidPragmaException;
use Railt\Compiler\Exception\LexerException;
use Railt\Compiler\Exception\UnrecognizedTokenException;
use Railt\Compiler\Lexer\Token;

/**
 * Class Lexer
 */
class Lexer implements \IteratorAggregate
{
    /**#@+
     * Token input definition indexes
     */
    public const INPUT_TOKEN_PATTERN            = 0;
    public const INPUT_TOKEN_CONTINUE_NAMESPACE = 1;
    public const INPUT_TOKEN_KEPT               = 2;
    /**#@-*/

    /**
     * @var string
     */
    private $input;

    /**
     * @var array
     */
    private $tokens;

    /**
     * @var bool
     */
    private $isUnicode;

    /**
     * @var string
     */
    private $errorUnrecognized;

    /**
     * @var bool
     */
    private $keepAll = false;

    /**
     * Lexer constructor.
     * @param string $input
     * @param array $tokens
     * @param array $pragmas
     * @throws \Railt\Compiler\Exception\InvalidPragmaException
     */
    public function __construct(string $input, array $tokens = [], array $pragmas = [])
    {
        $this->input  = $input;
        $this->tokens = $tokens;

        $this->isUnicode         = $this->isUnicode($pragmas);
        $this->errorUnrecognized = $this->getUnrecognizedToken($pragmas);
    }

    /**
     * @param bool $keep
     * @return Lexer
     */
    public function keepAll(bool $keep = true): self
    {
        $this->keepAll = $keep;

        return $this;
    }

    /**
     * @param array $pragmas
     * @return bool
     */
    private function isUnicode(array $pragmas): bool
    {
        $exists = \array_key_exists(Pragma::LEXER_UNICODE, $pragmas);

        if ($exists) {
            return (bool)$pragmas[Pragma::LEXER_UNICODE];
        }

        return true;
    }

    /**
     * @param array $pragmas
     * @return string
     * @throws \Railt\Compiler\Exception\InvalidPragmaException
     */
    private function getUnrecognizedToken(array $pragmas): string
    {
        $exists = \array_key_exists(Pragma::ERROR_UNRECOGNIZED_TOKEN, $pragmas);

        if ($exists) {
            $class = (string)$pragmas[Pragma::ERROR_UNRECOGNIZED_TOKEN];

            if (! \class_exists($class)) {
                $error = 'Invalid pragma "%s" value. Class "%s" does not exists.';
                throw new InvalidPragmaException(\sprintf($error, Pragma::ERROR_UNRECOGNIZED_TOKEN, $class));
            }

            return $class;
        }

        return UnrecognizedTokenException::class;
    }

    /**
     * @return \Traversable|\SplFixedArray
     * @throws \Railt\Compiler\Exception\LexerException
     */
    public function getIterator(): \Traversable
    {
        [$offset, $max] = [0, \strlen($this->input)];

        $namespace = Token::T_DEFAULT_NAMESPACE;

        while ($offset < $max) {
            [$next, $namespace] = $this->reduce($offset, $namespace);

            if ($next === null) {
                $error = \sprintf('Unrecognized token "%s"', $this->input[$offset]);
                throw new $this->errorUnrecognized($error, 0, null, [
                    'input'  => $this->input,
                    'offset' => $offset,
                ]);
            }

            if ($this->keepAll || $next[Token::T_KEEP]) {
                yield $next;
            }

            $offset += $next[Token::T_LENGTH];
        }

        yield Token::eof($offset);
    }

    /**
     * @param int $offset
     * @param string $namespace
     * @return array
     * @throws \Railt\Compiler\Exception\LexerException
     */
    private function reduce(int $offset, string $namespace): array
    {
        if (! \array_key_exists($namespace, $this->tokens)) {
            $error = \sprintf('Namespace "%s" does not exist', $namespace);
            throw new LexerException($error);
        }

        /** @var array $tokens */
        $tokens = $this->tokens[$namespace];

        foreach ($tokens as $name => $token) {
            $lexeme = $this->matchLexeme($name, $token[static::INPUT_TOKEN_PATTERN], $offset);

            if ($lexeme !== null) {
                $result = [
                    Token::T_TOKEN     => $name,
                    Token::T_VALUE     => $lexeme,
                    Token::T_LENGTH    => \strlen($lexeme),
                    Token::T_NAMESPACE => $namespace,
                    Token::T_KEEP      => $token[static::INPUT_TOKEN_KEPT] ?? true,
                    Token::T_OFFSET    => $offset,
                ];

                return [
                    $result,
                    $token[static::INPUT_TOKEN_CONTINUE_NAMESPACE] ?? $namespace,
                ];
            }
        }

        return [null, $namespace];
    }

    /**
     * Check if a given lexeme is matched at the beginning of the text.
     *
     * @param string $lexeme Name of the lexeme.
     * @param string $pattern Regular expression describing the lexeme.
     * @param int $offset Offset.
     * @return null|string
     * @throws LexerException
     */
    protected function matchLexeme(string $lexeme, string $pattern, int $offset): ?string
    {
        if (\preg_match($this->regex($pattern), $this->input, $matches, 0, $offset) === 0) {
            return null;
        }

        if ($matches[0] === '') {
            $error = 'A lexeme must not match an empty value, which is the case of "%s" (%s).';
            $error = \sprintf($error, $lexeme, $pattern);
            throw new LexerException($error);
        }

        return $matches[0];
    }

    /**
     * @param string $pattern
     * @return string
     */
    private function regex(string $pattern): string
    {
        $modifiers = $this->isUnicode ? 'u' : '';

        return '#\G(?|' . \str_replace('#', '\#', $pattern) . ')#' . $modifiers;
    }
}
