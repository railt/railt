<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

use Railt\Io\File;
use Railt\Lexer\LexerInterface;
use Railt\Lexer\Result\Unknown;
use Railt\Lexer\TokenInterface;
use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Exception\InternalException;
use Railt\Parser\Exception\ParserException;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\Exception\UnrecognizedTokenException;
use Railt\Parser\Finder\Depth;
use Railt\Parser\Finder\Filter;
use Railt\Parser\Finder\FinderLexer;

/**
 * Class Finder
 */
class Finder implements \IteratorAggregate
{
    /**
     * @var NodeInterface[]
     */
    private $rules;

    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * @var Depth
     */
    private $depth;

    /**
     * @var string|null
     */
    private $query;

    /**
     * Finder constructor.
     * @param NodeInterface ...$rules
     * @throws InternalException
     */
    public function __construct(NodeInterface ...$rules)
    {
        try {
            $this->rules = $rules;
            $this->depth = Depth::any();
            $this->lexer = new FinderLexer();
        } catch (\Throwable $e) {
            throw new InternalException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param NodeInterface ...$rules
     * @return Finder
     * @throws InternalException
     */
    public static function new(NodeInterface ...$rules): self
    {
        return new static(...$rules);
    }

    /**
     * @param int|null $to
     * @return Finder
     */
    public function depth(int $to = null): self
    {
        $this->depth->to($to);

        return $this;
    }

    /**
     * @param string $query
     * @param \Closure $then
     * @return Finder
     * @throws InternalException
     * @throws ParserException
     */
    public function when(string $query, \Closure $then): self
    {
        $nodes = \iterator_to_array($this->each($query, $then), false);

        return new static(...$nodes);
    }

    /**
     * @param string $query
     * @param \Closure $then
     * @return \Traversable
     * @throws InternalException
     * @throws ParserException
     */
    private function each(string $query, \Closure $then): \Traversable
    {
        foreach ($this->where($query)->all() as $rule) {
            $result = $then($rule);

            switch (true) {
                case \is_iterable($result):
                    yield from $result;
                    break;
                case (bool)$result:
                    yield $result;
                    break;
                default:
                    yield $rule;
            }
        }
    }

    /**
     * @return NodeInterface[]|RuleInterface[]|LeafInterface[]|iterable|\Generator
     * @throws InternalException
     * @throws ParserException
     */
    public function all(): iterable
    {
        try {
            [$expressions, $result] = [$this->expr($this->query()), null];

            foreach ($expressions as $expression) {
                $result = $result ? $this->unpack($result) : $result;
                $result = $result ?? $this->rules;
                $result = $this->exportEach($result, $expression, 0);
            }

            return $result;
        } catch (ParserException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $query
     * @return \Generator|Filter[]
     * @throws UnexpectedTokenException
     * @throws UnrecognizedTokenException
     */
    private function expr(string $query): \Generator
    {
        /**
         * @var TokenInterface $token
         * @var TokenInterface $lookahead
         */
        foreach ($this->lookahead($query) as $token => $lookahead) {
            switch ($lookahead->getName()) {
                case FinderLexer::T_ANY:
                    yield Filter::any($this->exprDepth($token));
                    break;

                case FinderLexer::T_NODE:
                    yield Filter::node($lookahead->getValue(1), $this->exprDepth($token));
                    break;

                case FinderLexer::T_LEAF:
                    yield Filter::leaf($lookahead->getValue(1), $this->exprDepth($token));
                    break;

                case FinderLexer::T_RULE:
                    yield Filter::rule($lookahead->getValue(1), $this->exprDepth($token));
                    break;
            }
        }
    }

    /**
     * @param string $query
     * @return iterable|TokenInterface[]
     * @throws UnexpectedTokenException
     * @throws UnrecognizedTokenException
     */
    private function lookahead(string $query): iterable
    {
        $file = File::fromSources($query, \sprintf('"%s"', \addcslashes($query, '"')));
        $tokens = $this->lexer->lookahead($file);

        /**
         * @var TokenInterface $token
         * @var TokenInterface $next
         */
        foreach ($tokens as $token => $next) {
            if ($next->getName() === Unknown::T_NAME) {
                $error = 'Unrecognized token %s';
                $exception = new UnrecognizedTokenException(\sprintf($error, $next));
                $exception->throwsIn($file, $next->getOffset());

                throw $exception;
            }

            if ($this->lexer->isExpression($token) && $this->lexer->isExpression($next)) {
                $error = 'Unexpected token %s';
                $exception = new UnexpectedTokenException(\sprintf($error, $next));
                $exception->throwsIn($file, $next->getOffset());

                throw $exception;
            }

            yield $token => $next;
        }
    }

    /**
     * @param TokenInterface|null $token
     * @return Depth
     */
    private function exprDepth(?TokenInterface $token): Depth
    {
        if ($token === null) {
            return $this->depth;
        }

        switch ($token->getName()) {
            case FinderLexer::T_DIRECT_DEPTH:
                return Depth::lte(1);
            case FinderLexer::T_EXACT_DEPTH:
                return Depth::equals((int)$token->getValue(1));
            default:
                return Depth::any();
        }
    }

    /**
     * @return string
     */
    private function query(): string
    {
        return $this->query ?? '*';
    }

    /**
     * @param iterable $nodes
     * @param Filter $filter
     * @param int $depth
     * @return iterable
     */
    private function exportEach(iterable $nodes, Filter $filter, int $depth): iterable
    {
        foreach ($nodes as $node) {
            yield from $this->export($node, $filter, $depth);
        }
    }

    /**
     * @param NodeInterface $node
     * @param Filter $filter
     * @param int $depth
     * @return iterable|NodeInterface[]
     */
    private function export(NodeInterface $node, Filter $filter, int $depth): iterable
    {
        if ($this->match($node, $filter, $depth)) {
            yield $node;
        }

        if ($node instanceof RuleInterface && $filter->depth->notFinished($depth)) {
            yield from $this->bypass($node, $filter, $depth + 1);
        }
    }

    /**
     * @param NodeInterface $node
     * @param Filter $filter
     * @param int $depth
     * @return bool
     */
    private function match(NodeInterface $node, Filter $filter, int $depth): bool
    {
        return $filter->match($node, $depth);
    }

    /**
     * @param RuleInterface $rule
     * @param Filter $filter
     * @param int $depth
     * @return iterable|NodeInterface[]
     */
    private function bypass(RuleInterface $rule, Filter $filter, int $depth): iterable
    {
        foreach ($rule->getChildren() as $child) {
            yield from $this->export($child, $filter, $depth);
        }
    }

    /**
     * @param iterable $result
     * @return iterable
     */
    private function unpack(iterable $result): iterable
    {
        foreach ($result as $child) {
            if ($child instanceof RuleInterface) {
                yield from $child;
            } else {
                yield $child;
            }
        }
    }

    /**
     * @param string $query
     * @return Finder
     */
    public function where(string $query): self
    {
        $this->query .= $query;

        return $this;
    }

    /**
     * @param int $group
     * @return null|string
     * @throws InternalException
     * @throws ParserException
     */
    public function value(int $group = 0): ?string
    {
        $result = $this->first();

        return $result ? $result->getValue($group) : null;
    }

    /**
     * @return null|NodeInterface
     * @throws InternalException
     * @throws ParserException
     */
    public function first(): ?NodeInterface
    {
        return $this->all()->current();
    }

    /**
     * @return \Generator|NodeInterface[]
     * @throws InternalException
     * @throws ParserException
     */
    public function getIterator(): \Generator
    {
        yield from $this->all();
    }
}
