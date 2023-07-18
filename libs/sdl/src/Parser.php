<?php

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Contracts\Exception\RuntimeExceptionInterface;
use Phplrt\Contracts\Parser\ParserInterface;
use Phplrt\Exception\RuntimeException;
use Phplrt\Lexer\Exception\UnrecognizedTokenException;
use Phplrt\Lexer\Lexer;
use Phplrt\Parser\ContextInterface;
use Phplrt\Parser\Exception\UnexpectedTokenException;
use Phplrt\Parser\Grammar\RuleInterface;
use Phplrt\Parser\Parser as ParserCombinator;
use Phplrt\Parser\ParserConfigsInterface;
use Phplrt\Position\Position;
use Railt\SDL\Exception\ParsingException;
use Railt\SDL\Parser\Builder;

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
final readonly class Parser implements ParserInterface
{
    private ParserInterface $parser;

    public function __construct()
    {
        /** @psalm-var GrammarConfigArray $grammar */
        $grammar = require __DIR__ . '/../resources/grammar.php';

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
     * @psalm-suppress MixedArgument
     */
    public function parse(mixed $source): iterable
    {
        try {
            return $this->parser->parse($source);
        } catch (RuntimeExceptionInterface $e) {
            $this->handle($e);
        }
    }

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
