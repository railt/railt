<?php

declare(strict_types=1);

namespace Railt\SDL\Parser;

use Phplrt\Contracts\Exception\RuntimeExceptionInterface;
use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Exception\RuntimeException;
use Phplrt\Lexer\Exception\UnrecognizedTokenException;
use Phplrt\Lexer\Lexer;
use Phplrt\Parser\ContextInterface;
use Phplrt\Parser\Exception\UnexpectedTokenException;
use Phplrt\Parser\Grammar\RuleInterface;
use Phplrt\Parser\Parser as ParserCombinator;
use Phplrt\Parser\ParserConfigsInterface;
use Phplrt\Position\Position;
use Psr\SimpleCache\InvalidArgumentException;
use Railt\SDL\Exception\ParsingException;
use Railt\SDL\Node\Expression\Literal\StringLiteralNode;
use Railt\SDL\Node\Node;

/**
 * @psalm-type GrammarConfigArray = array{
 *  initial: int<0, max>|non-empty-string,
 *  tokens: array{
 *      default: array<non-empty-string, non-empty-string>
 *  },
 *  skip: array<non-empty-string>,
 *  transitions: array,
 *  grammar: array<int<0, max>|non-empty-string, RuleInterface>,
 *  reducers: array<int<0, max>|non-empty-string, callable(ContextInterface, mixed):mixed>
 * }
 */
final class Parser implements ParserInterface
{
    /**
     * A string pool that contains the token as a key and the
     * processed {@see StringLiteralNode} object.
     *
     * Subsequent retrieval of an identical object from this pool will not
     * require the string to be parsed again.
     *
     * Note that the relation to this pool is available from within
     * the grammar `.pp2` files.
     *
     * @var \WeakMap<TokenInterface, StringLiteralNode>
     */
    private readonly \WeakMap $stringPool;

    /**
     * @var non-empty-string
     */
    public const DEFAULT_GRAMMAR_PATHNAME = __DIR__ . '/../../resources/grammar.php';

    private readonly ParserCombinator $parser;

    public function __construct()
    {
        /** @psalm-suppress PropertyTypeCoercion */
        $this->stringPool = new \WeakMap();

        /**
         * @psalm-var GrammarConfigArray $grammar
         * @psalm-suppress UnresolvableInclude
         */
        $grammar = require self::DEFAULT_GRAMMAR_PATHNAME;

        $this->parser = new ParserCombinator(
            lexer: new Lexer($grammar['tokens']['default'], $grammar['skip']),
            grammar: $grammar['grammar'],
            options: [
                ParserConfigsInterface::CONFIG_INITIAL_RULE => $grammar['initial'],
                ParserConfigsInterface::CONFIG_AST_BUILDER => new Builder($grammar['reducers']),
            ]
        );
    }

    /**
     * @return iterable<Node>
     *
     * @throws ParsingException
     * @throws InvalidArgumentException
     */
    public function parse(mixed $source): iterable
    {
        try {
            /** @var iterable<Node> */
            return $this->withoutRecursionDepth(function () use ($source): iterable {
                /** @psalm-suppress MixedArgument : Yep, its mixed */
                return $this->parser->parse($source);
            });
        } catch (RuntimeExceptionInterface $e) {
            $this->handle($e);
        }
    }

    /**
     * @template TResult of mixed
     *
     * @param callable():TResult $context
     *
     * @return TResult
     */
    private function withoutRecursionDepth(callable $context): mixed
    {
        if (($beforeRecursionDepth = \ini_get('xdebug.max_nesting_level')) !== false) {
            \ini_set('xdebug.max_nesting_level', -1);
        }

        if (($beforeMode = \ini_get('xdebug.mode')) !== false) {
            \ini_set('xdebug.mode', 'off');
        }

        try {
            return $context();
        } finally {
            if ($beforeRecursionDepth !== false) {
                \ini_set('xdebug.max_nesting_level', $beforeRecursionDepth);
            }

            if ($beforeMode !== false) {
                \ini_set('xdebug.mode', $beforeMode);
            }
        }
    }

    /**
     * @throws ParsingException
     */
    private function handle(RuntimeExceptionInterface $e): never
    {
        $token = $e->getToken();
        $source = $e->getSource();
        $message = $e instanceof RuntimeException ? $e->getOriginalMessage() : $e->getMessage();
        $position = Position::fromOffset($source, $token->getOffset());

        if ($e instanceof UnexpectedTokenException) {
            throw ParsingException::fromUnexpectedToken($message, $source, $position);
        }

        if ($e instanceof UnrecognizedTokenException) {
            throw ParsingException::fromUnrecognizedToken($message, $source, $position);
        }

        throw ParsingException::fromGenericError($message, $source, $position);
    }
}
