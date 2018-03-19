<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser;

use Railt\Compiler\Iterator\BufferedIterator;
use Railt\Compiler\LexerInterface;
use Railt\Compiler\ParserInterface;
use Railt\Compiler\Parser\Ast\Leaf;
use Railt\Compiler\Parser\Ast\Node;
use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Ast\Rule as AstRule;
use Railt\Compiler\Parser\Rule\Choice;
use Railt\Compiler\Parser\Rule\Concatenation;
use Railt\Compiler\Parser\Rule\Escape;
use Railt\Compiler\Parser\Rule\Entry;
use Railt\Compiler\Parser\Rule\Repetition;
use Railt\Compiler\Parser\Rule\Rule;
use Railt\Compiler\Parser\Rule\Terminal;
use Railt\Io\Readable;

/**
 * Class Parser
 */
class Parser implements ParserInterface
{
    /**
     * Rules, to be defined as associative array, name => Rule object.
     *
     * @var array
     */
    protected $rules;

    /**
     * Lexer iterator.
     *
     * @var BufferedIterator
     */
    protected $buffer;

    /**
     * Trace of activated rules.
     *
     * @var array
     */
    protected $trace = [];

    /**
     * Stack of todo list.
     *
     * @var array
     */
    protected $todo;

    /**
     * Current depth while building the trace.
     *
     * @var int
     */
    protected $depth = -1;

    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * Construct the parser.
     *
     * @param LexerInterface $lexer
     * @param array $rules Rules.
     */
    public function __construct(LexerInterface $lexer, array $rules = [])
    {
        $this->rules   = $rules;
        $this->lexer = $lexer;
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
     * @return BufferedIterator
     */
    private function getBuffer(Readable $input): BufferedIterator
    {
        return new BufferedIterator($this->lex($input), 1024);
    }

    /**
     * @param Readable $input
     * @return \Traversable|\Iterator
     */
    private function lex(Readable $input): \Traversable
    {
        yield from $this->lexer->lex($input);
        yield \Railt\Compiler\Lexer\Token::eof(0);
    }

    /**
     * Parse :-).
     *
     * @param Readable $input
     * @return mixed
     */
    public function parse(Readable $input): NodeInterface
    {
        $this->buffer = $this->getBuffer($input);
        $this->buffer->rewind();

        $this->trace      = [];
        $this->todo       = [];

        $rule = $this->getRootRule();

        $closeRule  = new Escape($rule, 0);
        $openRule   = new Entry($rule, 0, [$closeRule]);
        $this->todo = [$closeRule, $openRule];

        do {
            $out = $this->unfold();

            if ($out !== null && $this->buffer->current()->isEof()) {
                break;
            }

            if ($this->backtrack() === false) {
                $token = $this->buffer->current();

                $error = \sprintf('Unexpected token %s', $token);
                throw new \RuntimeException($error);
            }
        } while (true);

        $ast = $this->buildTree();

        if (! ($ast instanceof NodeInterface)) {
            throw new \RuntimeException('Parsing error: cannot build AST, the trace is corrupted.', 1);
        }

        return $ast;
    }

    /**
     * Get root rule.
     *
     * @return string
     */
    public function getRootRule(): string
    {
        foreach ($this->rules as $rule => $_) {
            if (\is_string($rule)) {
                return $rule;
            }
        }

        throw new \RuntimeException('Invalid grammar root rule (Can not find)');
    }

    /**
     * Unfold trace.
     *
     * @return  mixed
     */
    protected function unfold()
    {
        while (0 < \count($this->todo)) {
            $rule = \array_pop($this->todo);

            if ($rule instanceof Escape) {
                $rule->setDepth($this->depth);
                $this->trace[] = $rule;

                if ($rule->isTransitional() === false) {
                    --$this->depth;
                }
            } else {
                $ruleName = $rule->getRule();
                $next     = $rule->getData();
                $zeRule   = $this->rules[$ruleName];
                $out      = $this->parseCurrentRule($zeRule, $next);

                if ($out === false && $this->backtrack() === false) {
                    return;
                }
            }
        }

        return true;
    }

    /**
     * Parse current rule.
     *
     * @param Rule $zeRule Current rule.
     * @param int $next Next rule index.
     * @return bool
     */
    protected function parseCurrentRule(Rule $zeRule, $next)
    {
        if ($zeRule instanceof Terminal) {
            $name = $this->buffer->current()->name();

            if ($zeRule->getTokenName() !== $name) {
                return false;
            }

            $value = $this->buffer->current()->value();

            if (0 <= $unification = $zeRule->getUnificationIndex()) {
                for ($skip = 0, $i = \count($this->trace) - 1; $i >= 0; --$i) {
                    $trace = $this->trace[$i];

                    if ($trace instanceof Entry) {
                        if ($trace->isTransitional() === false) {
                            if ($trace->getDepth() <= $this->depth) {
                                break;
                            }

                            --$skip;
                        }
                    } elseif ($trace instanceof Escape &&
                        $trace->isTransitional() === false) {
                        $skip += $trace->getDepth() > $this->depth;
                    }

                    if (0 < $skip) {
                        continue;
                    }

                    if ($trace instanceof Terminal &&
                        $unification === $trace->getUnificationIndex() &&
                        $value !== $trace->getValue()) {
                        return false;
                    }
                }
            }

            $current = $this->buffer->current();

            $offset    = $current->offset();

            $zzeRule = clone $zeRule;
            $zzeRule->setValue($value);
            $zzeRule->setOffset($offset);

            \array_pop($this->todo);
            $this->trace[] = $zzeRule;
            $this->buffer->next();

            return true;
        }

        if ($zeRule instanceof Concatenation) {
            $this->trace[] = new Entry(
                $zeRule->getName(),
                0,
                null,
                $this->depth
            );
            $children      = $zeRule->getChildren();

            for ($i = \count($children) - 1; $i >= 0; --$i) {
                $nextRule     = $children[$i];
                $this->todo[] = new Escape($nextRule, 0);
                $this->todo[] = new Entry($nextRule, 0);
            }

            return true;
        }

        if ($zeRule instanceof Choice) {
            $children = $zeRule->getChildren();

            if ($next >= \count($children)) {
                return false;
            }

            $this->trace[] = new Entry(
                $zeRule->getName(),
                $next,
                $this->todo,
                $this->depth
            );
            $nextRule      = $children[$next];
            $this->todo[]  = new Escape($nextRule, 0);
            $this->todo[]  = new Entry($nextRule, 0);

            return true;
        }

        if ($zeRule instanceof Repetition) {
            $nextRule = $zeRule->getChildren();

            if ($next === 0) {
                $name = $zeRule->getName();
                $min  = $zeRule->getMin();

                $this->trace[] = new Entry(
                    $name,
                    $min,
                    null,
                    $this->depth
                );
                \array_pop($this->todo);
                $this->todo[] = new Escape(
                    $name,
                    $min,
                    $this->todo
                );

                for ($i = 0; $i < $min; ++$i) {
                    $this->todo[] = new Escape($nextRule, 0);
                    $this->todo[] = new Entry($nextRule, 0);
                }

                return true;
            }
            $max = $zeRule->getMax();

            if ($max != -1 && $next > $max) {
                return false;
            }

            $this->todo[] = new Escape(
                $zeRule->getName(),
                $next,
                $this->todo
            );
            $this->todo[] = new Escape($nextRule, 0);
            $this->todo[] = new Entry($nextRule, 0);

            return true;
        }

        return false;
    }

    /**
     * Backtrack the trace.
     *
     * @return bool
     */
    protected function backtrack(): bool
    {
        $found = false;

        do {
            $last = \array_pop($this->trace);

            if ($last instanceof Entry) {
                $zeRule = $this->rules[$last->getRule()];
                $found  = $zeRule instanceof Choice;
            } elseif ($last instanceof Escape) {
                $zeRule = $this->rules[$last->getRule()];
                $found  = $zeRule instanceof Repetition;
            } elseif ($last instanceof Terminal) {
                $this->buffer->previous();

                if ($this->buffer->valid() === false) {
                    return false;
                }
            }
        } while (0 < \count($this->trace) && $found === false);

        if ($found === false) {
            return false;
        }

        $rule         = $last->getRule();
        $next         = $last->getData() + 1;
        $this->depth  = $last->getDepth();
        $this->todo   = $last->getTodo();
        $this->todo[] = new Entry($rule, $next);

        return true;
    }

    /**
     * Build AST from trace.
     * Walk through the trace iteratively and recursively.
     *
     * @param int $i Current trace index.
     * @param array &$children Collected children.
     * @return Node|int
     */
    protected function buildTree($i = 0, array &$children = [])
    {
        $max = \count($this->trace);

        while ($i < $max) {
            $trace = $this->trace[$i];

            if ($trace instanceof Entry) {
                $ruleName  = $trace->getRule();
                $rule      = $this->rules[$ruleName];
                $isRule    = $trace->isTransitional() === false;
                $nextTrace = $this->trace[$i + 1];
                $id        = $rule->getNodeId();

                // Optimization: Skip empty trace sequence.
                if ($nextTrace instanceof Escape && $ruleName === $nextTrace->getRule()) {
                    $i += 2;

                    continue;
                }

                if ($isRule === true) {
                    $children[] = $ruleName;
                }

                if ($id !== null) {
                    $children[] = [
                        'id'      => $id,
                        'options' => $rule->getNodeOptions(),
                    ];
                }

                $i = $this->buildTree($i + 1, $children);

                if ($isRule === false) {
                    continue;
                }

                $handle = [];
                $cId    = null;

                do {
                    $pop = \array_pop($children);

                    if (\is_object($pop) === true) {
                        $handle[] = $pop;
                    } elseif (\is_array($pop) === true && $cId === null) {
                        $cId = $pop['id'];
                    } elseif ($ruleName === $pop) {
                        break;
                    }
                } while ($pop !== null);

                if ($cId === null) {
                    $cId = $rule->getDefaultId();
                }

                if ($cId === null) {
                    for ($j = \count($handle) - 1; $j >= 0; --$j) {
                        $children[] = $handle[$j];
                    }

                    continue;
                }

                $cTree      = new AstRule((string)($id ?: $cId), \array_reverse($handle));
                $children[] = $cTree;
            } elseif ($trace instanceof Escape) {
                return $i + 1;
            } else {
                if ($trace->isKept() === false) {
                    ++$i;

                    continue;
                }

                $child = new Leaf(
                    $trace->getTokenName(),
                    $trace->getValue(),
                    $trace->getOffset()
                );

                $children[] = $child;
                ++$i;
            }
        }

        return $children[0];
    }

    /**
     * Try to merge directly children into an existing node.
     *
     * @param array &$children Current children being gathering.
     * @param array &$handle Children of the new node.
     * @param string $cId Node ID.
     * @return  bool
     */
    protected function mergeTree(&$children, &$handle, $cId)
    {
        \end($children);
        $last = \current($children);

        if (! \is_object($last)) {
            return false;
        }

        if ($cId !== $last->getId()) {
            return false;
        }

        foreach ($handle as $child) {
            $last->appendChild($child);
            $child->setParent($last);
        }

        return true;
    }

    /**
     * Get trace.
     *
     * @return  array
     */
    public function getTrace()
    {
        return $this->trace;
    }

    /**
     * Get rule by name.
     *
     * @param $name
     * @return Rule|null
     */
    public function getRule($name): ?Rule
    {
        return $this->rules[$name] ?? null;
    }

    /**
     * Get rules.
     *
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
