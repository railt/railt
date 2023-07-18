<?php

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Contracts\Exception\RuntimeExceptionInterface;
use Phplrt\Contracts\Parser\ParserInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Exception\RuntimeException;
use Phplrt\Lexer\Exception\UnrecognizedTokenException;
use Phplrt\Lexer\Lexer;
use Phplrt\Parser\ContextInterface;
use Phplrt\Parser\Exception\UnexpectedTokenException;
use Phplrt\Parser\Grammar\RuleInterface;
use Phplrt\Parser\Parser as ParserCombinator;
use Phplrt\Parser\ParserConfigsInterface;
use Phplrt\Position\Position;
use Phplrt\Source\File;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Railt\SDL\Exception\ParsingException;
use Railt\SDL\Node\Node;
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
final class Parser implements ParserInterface
{
    private readonly ParserInterface $parser;

    public function __construct(
        private readonly ?CacheInterface $cache = null,
    ) {
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
     * @return non-empty-string
     */
    private function getKey(ReadableInterface $source): string
    {
        return 'railt.ast.' . \hash('xxh64', $source->getHash());
    }

    /**
     * @return iterable<Node>
     *
     * @throws ParsingException
     * @throws InvalidArgumentException
     */
    public function parse(mixed $source): iterable
    {
        $key = $this->getKey($source = File::new($source));

        if ($this->cache?->has($key)) {
            /** @var iterable<Node> */
            return $this->cache->get($key);
        }

        try {
            /** @var iterable<Node> $result */
            $result = $this->withoutRecursionDepth(function () use ($source): iterable {
                return $this->parser->parse($source);
            });

            /** @psalm-suppress all : cache may be null */
            $this->cache?->set($key, $result);

            return $result;
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
