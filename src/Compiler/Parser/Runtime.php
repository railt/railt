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
use Railt\Compiler\Parser\Ast\Leaf;
use Railt\Compiler\Parser\Ast\Node;
use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Ast\Rule as AstRule;
use Railt\Compiler\Parser\Rule\Choice;
use Railt\Compiler\Parser\Rule\Concatenation;
use Railt\Compiler\Parser\Rule\Entry;
use Railt\Compiler\Parser\Rule\Escape;
use Railt\Compiler\Parser\Rule\Repetition;
use Railt\Compiler\Parser\Rule\Rule;
use Railt\Compiler\Parser\Rule\Terminal;
use Railt\Compiler\ParserInterface;
use Railt\Io\Readable;

/**
 * Class Parser
 */
abstract class Runtime implements ParserInterface
{
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
     * @var array
     */
    private $rules;

    /**
     * Construct the parser.
     *
     * @param LexerInterface $lexer
     * @param array $rules Rules.
     */
    public function __construct(LexerInterface $lexer, array $rules = [])
    {
        $this->rules = $rules;
        $this->lexer = $lexer;
    }

    /**
     * Parse :-).
     *
     * @param Readable $input
     * @return mixed
     */
    public function parse(Readable $input): NodeInterface
    {
        $buffer = new BufferedIterator($this->lex($input), 1024);
        $buffer->rewind();

        $this->trace = [];
        $this->todo  = [];

        $rule = $this->getRootRule();

        $closeRule  = new Escape($rule, 0);
        $openRule   = new Entry($rule, 0, [$closeRule]);
        $this->todo = [$closeRule, $openRule];

        do {
            $out = $this->unfold($buffer);

            if ($out !== null && $buffer->current()->isEof()) {
                break;
            }

            if ($this->backtrack($buffer) === false) {
                $token = $buffer->current();

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
     * @param Readable $input
     * @return \Traversable
     */
    private function lex(Readable $input): \Traversable
    {
        yield from $this->getLexer()->lex($input);
    }

    /**
     * @return LexerInterface
     */
    public function getLexer(): LexerInterface
    {
        return $this->lexer;
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
     * Unfold trace
     * @param BufferedIterator $buffer
     * @return mixed
     */
    protected function unfold(BufferedIterator $buffer)
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
                $out = $this->parseCurrentRule($buffer, $this->getRule($rule->getRule()), $rule->getData());

                if ($out === false && $this->backtrack($buffer) === false) {
                    return;
                }
            }
        }

        return true;
    }

    /**
     * Parse current rule
     * @param BufferedIterator $buffer
     * @param Rule $rule Current rule.
     * @param int $next Next rule index.
     * @return bool
     */
    protected function parseCurrentRule(BufferedIterator $buffer, Rule $rule, $next): bool
    {
        if ($rule instanceof Terminal) {
            $name = $buffer->current()->name();

            if ($rule->getTokenName() !== $name) {
                return false;
            }

            $value = $buffer->current()->value();

            $current = $buffer->current();

            $offset = $current->offset();

            $leaf = clone $rule;
            $leaf->setValue($value);
            $leaf->setOffset($offset);

            \array_pop($this->todo);
            $this->trace[] = $leaf;
            $buffer->next();

            return true;
        }

        if ($rule instanceof Concatenation) {
            $this->trace[] = new Entry($rule->getName(), 0, null, $this->depth);
            $children      = $rule->getChildren();

            for ($i = \count($children) - 1; $i >= 0; --$i) {
                $nextRule     = $children[$i];
                $this->todo[] = new Escape($nextRule, 0);
                $this->todo[] = new Entry($nextRule, 0);
            }

            return true;
        }

        if ($rule instanceof Choice) {
            $children = $rule->getChildren();

            if ($next >= \count($children)) {
                return false;
            }

            $this->trace[] = new Entry($rule->getName(), $next, $this->todo, $this->depth);
            $nextRule      = $children[$next];
            $this->todo[]  = new Escape($nextRule, 0);
            $this->todo[]  = new Entry($nextRule, 0);

            return true;
        }

        if ($rule instanceof Repetition) {
            $nextRule = $rule->getChildren();

            if ($next === 0) {
                $name = $rule->getName();
                $min  = $rule->getMin();

                $this->trace[] = new Entry($name, $min, null, $this->depth);
                \array_pop($this->todo);
                $this->todo[] = new Escape($name, $min, $this->todo);

                for ($i = 0; $i < $min; ++$i) {
                    $this->todo[] = new Escape($nextRule, 0);
                    $this->todo[] = new Entry($nextRule, 0);
                }

                return true;
            }

            $max = $rule->getMax();

            if ($max !== Repetition::INF_MAX_VALUE && $next > $max) {
                return false;
            }

            $this->todo[] = new Escape($rule->getName(), $next, $this->todo);
            $this->todo[] = new Escape($nextRule, 0);
            $this->todo[] = new Entry($nextRule, 0);

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
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
     * Backtrack the trace.
     *
     * @param BufferedIterator $buffer
     * @return bool
     */
    protected function backtrack(BufferedIterator $buffer): bool
    {
        $found = false;

        do {
            $last = \array_pop($this->trace);

            if ($last instanceof Entry) {
                $found = $this->getRule($last->getRule()) instanceof Choice;
            } elseif ($last instanceof Escape) {
                $found = $this->getRule($last->getRule()) instanceof Repetition;
            } elseif ($last instanceof Terminal) {
                $buffer->previous();

                if ($buffer->valid() === false) {
                    return false;
                }
            }
        } while (0 < \count($this->trace) && $found === false);

        if ($found === false) {
            return false;
        }

        $this->depth  = $last->getDepth();
        $this->todo   = $last->getTodo();
        $this->todo[] = new Entry($last->getRule(), $last->getData() + 1);

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

                $children[] = new Leaf($trace->getTokenName(), $trace->getValue(), $trace->getOffset());
                ++$i;
            }
        }

        return $children[0];
    }
}
