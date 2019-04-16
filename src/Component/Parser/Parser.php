<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Parser;

use Railt\Component\Io\Readable;
use Railt\Component\Lexer\LexerInterface;
use Railt\Component\Lexer\Token\Unknown;
use Railt\Component\Lexer\TokenInterface;
use Railt\Component\Parser\Ast\Builder;
use Railt\Component\Parser\Ast\BuilderInterface;
use Railt\Component\Parser\Ast\RuleInterface;
use Railt\Component\Parser\Exception\GrammarException;
use Railt\Component\Parser\Exception\UnexpectedTokenException;
use Railt\Component\Parser\Rule\Alternation;
use Railt\Component\Parser\Rule\Concatenation;
use Railt\Component\Parser\Rule\Repetition;
use Railt\Component\Parser\Rule\Rule;
use Railt\Component\Parser\Rule\Terminal;
use Railt\Component\Parser\TokenStream\TokenStream;
use Railt\Component\Parser\Trace\Entry;
use Railt\Component\Parser\Trace\Escape;
use Railt\Component\Parser\Trace\Token;
use Railt\Component\Parser\Trace\TraceItem;

/**
 * Class Parser
 */
class Parser implements ParserInterface
{
    /**
     * @var LexerInterface
     */
    protected $lexer;

    /**
     * @var GrammarInterface
     */
    protected $grammar;

    /**
     * Lexer iterator
     *
     * @var TokenStream
     */
    protected $stream;

    /**
     * Possible token causing an error
     *
     * @var TokenInterface|null
     */
    private $errorToken;

    /**
     * Trace of parsed rules
     *
     * @var array|TraceItem[]
     */
    protected $trace = [];

    /**
     * Stack of items which need to be processed
     *
     * @var \SplStack|TraceItem[]
     */
    private $todo;

    /**
     * AbstractParser constructor.
     *
     * @param LexerInterface $lexer
     * @param GrammarInterface $grammar
     */
    public function __construct(LexerInterface $lexer, GrammarInterface $grammar)
    {
        $this->lexer = $lexer;
        $this->grammar = $grammar;
    }

    /**
     * @param string $ruleId
     * @param \Closure $then
     * @return ParserInterface|$this
     * @throws GrammarException
     */
    public function extend(string $ruleId, \Closure $then): ParserInterface
    {
        $maxId = \count($this->grammar->getRules()) - 1;

        $result = $then($this->grammar->fetch($ruleId), $maxId);

        if ($result instanceof \Generator) {
            while ($result->valid()) {
                [$key, $value] = [$result->key(), $result->current()];

                switch (true) {
                    case $value instanceof Rule:
                        $this->grammar->addRule($value);
                        $value = $value->getName();
                        break;

                    case \is_string($key) && \is_string($value):
                        if (! \class_exists($value)) {
                            throw new GrammarException('Delegate class ' . $value . '::class not found');
                        }

                        $this->grammar->addDelegate($key, $value);
                        break;

                    default:
                        throw new GrammarException('Bad parser extension generator arguments');
                }

                $result->send($value);
            }
        }

        return $this;
    }

    /**
     * @return GrammarInterface
     */
    public function getGrammar(): GrammarInterface
    {
        return $this->grammar;
    }

    /**
     * @return LexerInterface
     */
    public function getLexer(): LexerInterface
    {
        return $this->lexer;
    }

    /**
     * @param Readable $input
     * @return RuleInterface|mixed
     * @throws \Railt\Component\Exception\ExternalException
     */
    public function parse(Readable $input)
    {
        return $this->build($this->trace($input));
    }

    /**
     * @param array $trace
     * @return mixed
     */
    protected function build(array $trace)
    {
        return $this->getBuilder($trace)->build();
    }

    /**
     * @param array $trace
     * @return BuilderInterface
     */
    protected function getBuilder(array $trace): BuilderInterface
    {
        return new Builder($trace, $this->grammar);
    }

    /**
     * @param Readable $input
     * @return array
     * @throws \Railt\Component\Exception\ExternalException
     */
    protected function trace(Readable $input): array
    {
        $this->reset($input);
        $this->prepare();

        do {
            if ($this->unfold() && $this->stream->isEoi()) {
                break;
            }

            $this->verifyBacktrace($input);
        } while (true);

        return $this->trace;
    }

    /**
     * @param Readable $input
     * @throws \Railt\Component\Exception\ExternalException
     */
    private function reset(Readable $input): void
    {
        $this->stream = $this->getStream($input);

        $this->errorToken = null;

        $this->trace = [];
        $this->todo = [];
    }

    /**
     * @param Readable $input
     * @return TokenStream
     * @throws \Railt\Component\Exception\ExternalException
     */
    protected function getStream(Readable $input): TokenStream
    {
        return new TokenStream($this->lex($input), \PHP_INT_MAX);
    }

    /**
     * @param Readable $input
     * @return iterable|TokenInterface[]
     * @throws UnexpectedTokenException
     */
    protected function lex(Readable $input): iterable
    {
        foreach ($this->lexer->lex($input) as $token) {
            if ($token->getName() === Unknown::T_NAME) {
                $exception = new UnexpectedTokenException(\sprintf('Unexpected token %s', $token));
                $exception->throwsIn($input, $token->getOffset());

                throw $exception;
            }

            yield $token;
        }
    }

    /**
     * @return void
     */
    private function prepare(): void
    {
        $openRule = new Entry($this->grammar->beginAt(), 0, [
            $closeRule = new Escape($this->grammar->beginAt(), 0),
        ]);

        $this->todo = [$closeRule, $openRule];
    }

    /**
     * Unfold trace.
     *
     * @return bool
     */
    private function unfold(): bool
    {
        while (0 < \count($this->todo)) {
            $rule = \array_pop($this->todo);

            if ($rule instanceof Escape) {
                $this->addTrace($rule);
            } else {
                $out = $this->reduce($this->grammar->fetch($rule->getRule()), $rule->getData());

                if ($out === false && $this->backtrack() === false) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param TraceItem $item
     * @return TraceItem
     */
    private function addTrace(TraceItem $item): TraceItem
    {
        $this->trace[] = $item;

        $item->at($this->stream->offset());

        return $item;
    }

    /**
     * @param Rule $current
     * @param string|int $next
     * @return bool
     */
    private function reduce(Rule $current, $next): bool
    {
        if (! $this->stream->current()) {
            return false;
        }

        switch (true) {
            case $current instanceof Terminal:
                return $this->parseTerminal($current);

            case $current instanceof Concatenation:
                return $this->parseConcatenation($current);

            case $current instanceof Alternation:
                return $this->parseAlternation($current, $next);

            case $current instanceof Repetition:
                return $this->parseRepetition($current, $next);
        }

        return false;
    }

    /**
     * @param Terminal $token
     * @return bool
     */
    private function parseTerminal(Terminal $token): bool
    {
        /** @var TokenInterface $current */
        $current = $this->stream->current();

        if ($token->getTokenName() !== $current->getName()) {
            return false;
        }

        \array_pop($this->todo);

        $this->addTrace(new Token($current, $token->isKept()));
        $this->errorToken = $this->stream->next();

        return true;
    }

    /**
     * @param Concatenation $concat
     * @return bool
     */
    private function parseConcatenation(Concatenation $concat): bool
    {
        $this->addTrace(new Entry($concat->getName()));

        $children = $concat->getChildren();

        for ($i = \count($children) - 1; $i >= 0; --$i) {
            $nextRule = $children[$i];

            $this->todo[] = new Escape($nextRule, 0);
            $this->todo[] = new Entry($nextRule, 0);
        }

        return true;
    }

    /**
     * @param Alternation $choice
     * @param string|int $next
     * @return bool
     */
    private function parseAlternation(Alternation $choice, $next): bool
    {
        $children = $choice->getChildren();

        if ($next >= \count($children)) {
            return false;
        }

        $this->addTrace(new Entry($choice->getName(), $next, $this->todo));

        $nextRule = $children[$next];

        $this->todo[] = new Escape($nextRule, 0);
        $this->todo[] = new Entry($nextRule, 0);

        return true;
    }

    /**
     * @param Repetition $repeat
     * @param string|int $next
     * @return bool
     */
    private function parseRepetition(Repetition $repeat, $next): bool
    {
        $nextRule = $repeat->getChildren();

        if ($next === 0) {
            $name = $repeat->getName();
            $min = $repeat->getMin();

            $this->addTrace(new Entry($name, $min));

            \array_pop($this->todo);

            $this->todo[] = new Escape($name, $min, $this->todo);

            for ($i = 0; $i < $min; ++$i) {
                $this->todo[] = new Escape($nextRule, 0);
                $this->todo[] = new Entry($nextRule, 0);
            }

            return true;
        }

        $max = $repeat->getMax();

        if ($max !== -1 && $next > $max) {
            return false;
        }

        $this->todo[] = new Escape($repeat->getName(), $next, $this->todo);
        $this->todo[] = new Escape($nextRule, 0);
        $this->todo[] = new Entry($nextRule, 0);

        return true;
    }

    /**
     * Backtrack the trace.
     *
     * @return bool
     */
    private function backtrack(): bool
    {
        $found = false;

        do {
            $last = \array_pop($this->trace);

            if ($last instanceof Entry) {
                $found = $this->grammar->fetch($last->getRule()) instanceof Alternation;
            } elseif ($last instanceof Escape) {
                $found = $this->grammar->fetch($last->getRule()) instanceof Repetition;
            } elseif ($last instanceof Token) {
                if (! $this->stream->prev()) {
                    return false;
                }
            }
        } while (0 < \count($this->trace) && $found === false);

        if ($found === false) {
            return false;
        }

        $this->todo = $last->getTodo();
        $this->todo[] = new Entry($last->getRule(), $last->getData() + 1);

        return true;
    }

    /**
     * @param Readable $input
     * @throws \Railt\Component\Exception\ExternalException
     */
    private function verifyBacktrace(Readable $input): void
    {
        if ($this->backtrack() === false) {
            /** @var TokenInterface $token */
            $token = $this->errorToken ?? $this->stream->current();

            $exception = new UnexpectedTokenException(\sprintf('Unexpected token %s', $token));
            $exception->throwsIn($input, $token->getOffset());

            throw $exception;
        }
    }
}
