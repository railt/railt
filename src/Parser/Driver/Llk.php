<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Driver;

use Railt\Io\Readable;
use Railt\Lexer\TokenInterface;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\Rule\Alternation;
use Railt\Parser\Rule\Concatenation;
use Railt\Parser\Rule\Repetition;
use Railt\Parser\Rule\Rule;
use Railt\Parser\Rule\Terminal;
use Railt\Parser\TokenStream\TokenStream;
use Railt\Parser\Trace\Entry;
use Railt\Parser\Trace\Escape;
use Railt\Parser\Trace\Token;
use Railt\Parser\Trace\TraceItem;

/**
 * Class Llk
 */
class Llk extends AbstractParser
{
    /**
     * Lexer iterator
     *
     * @var TokenStream
     */
    private $stream;

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
    private $trace = [];

    /**
     * Stack of items which need to be processed
     *
     * @var \SplStack|TraceItem[]
     */
    private $todo;

    /**
     * @param Readable $input
     * @return iterable
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function trace(Readable $input): iterable
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
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function verifyBacktrace(Readable $input): void
    {
        if ($this->backtrack() === false) {
            /** @var TokenInterface $token */
            $token = $this->errorToken ?? $this->stream->current();

            throw (new UnexpectedTokenException(\sprintf('Unexpected token %s', $token)))
                ->throwsIn($input, $token->getOffset());
        }
    }

    /**
     * @param Readable $input
     */
    private function reset(Readable $input): void
    {
        $this->stream = $this->stream($input);

        $this->errorToken = null;

        $this->trace = [];
        $this->todo  = [];
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
            $min  = $repeat->getMin();

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

        $this->todo   = $last->getTodo();
        $this->todo[] = new Entry($last->getRule(), $last->getData() + 1);

        return true;
    }
}
