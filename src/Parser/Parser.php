<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

use Hoa\Iterator\Buffer;
use Railt\Parser\Exception\Exception;
use Railt\Parser\Exception\UnexpectedToken;
use Railt\Parser\Rule\Choice;
use Railt\Parser\Rule\Concatenation;
use Railt\Parser\Rule\Ekzit;
use Railt\Parser\Rule\Entry;
use Railt\Parser\Rule\Repetition;
use Railt\Parser\Rule\Rule;
use Railt\Parser\Rule\Token;

/**
 * Class \Railt\Parser\Parser.
 *
 * LL(k) parser.
 *
 * @copyright Copyright © 2007-2017 Hoa community
 * @license New BSD License
 */
class Parser
{
    /**
     * List of pragmas.
     *
     * @var array
     */
    protected $pragmas;

    /**
     * List of skipped tokens.
     *
     * @var array
     */
    protected $skip;

    /**
     * Associative array (token name => token regex), to be defined in
     * precedence order.
     *
     * @var array
     */
    protected $tokens;

    /**
     * Rules, to be defined as associative array, name => Rule object.
     *
     * @var array
     */
    protected $rules;

    /**
     * Lexer iterator.
     *
     * @var Buffer
     */
    protected $buffer;

    /**
     * Possible token causing an error.
     *
     * @var array
     */
    protected $errorToken;

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
     * AST.
     *
     * @var \Railt\Parser\TreeNode
     */
    protected $tree;

    /**
     * Current depth while building the trace.
     *
     * @var int
     */
    protected $depth = -1;

    /**
     * Construct the parser.
     *
     * @param array $tokens Tokens.
     * @param array $rules Rules.
     * @param array $pragmas Pragmas.
     */
    public function __construct(array $tokens = [], array $rules = [], array $pragmas = [])
    {
        $this->tokens  = $tokens;
        $this->rules   = $rules;
        $this->pragmas = $pragmas;
    }

    /**
     * Parse :-).
     *
     * @param string $text Text to parse.
     * @param string $rule The axiom, i.e. root rule.
     * @param bool $tree Whether build tree or not.
     * @return  mixed
     * @throws  UnexpectedToken
     */
    public function parse(string $text, $rule = null, $tree = true)
    {
        $lexer        = new Lexer($this->pragmas);
        $this->buffer = new Buffer(
            $lexer->lexMe($text, $this->tokens),
            Pragma::getLookahead($this->pragmas)
        );
        $this->buffer->rewind();

        $this->errorToken = null;
        $this->trace      = [];
        $this->todo       = [];

        if (\array_key_exists($rule, $this->rules) === false) {
            $rule = $this->getRootRule();
        }

        $closeRule  = new Ekzit($rule, 0);
        $openRule   = new Entry($rule, 0, [$closeRule]);
        $this->todo = [$closeRule, $openRule];

        do {
            $out = $this->unfold();

            if ($out !== null &&
                $this->buffer->current()['token'] === 'EOF') {
                break;
            }

            if ($this->backtrack() === false) {
                $token = $this->errorToken;

                if ($this->errorToken === null) {
                    $token = $this->buffer->current();
                }

                $error = \vsprintf('Unexpected token "%s" (%s)', [
                    $token['value'],
                    $token['token'],
                ]);

                throw UnexpectedToken::fromOffset($error, $text, $token['offset']);
            }
        } while (true);

        if ($tree === false) {
            return true;
        }

        $tree = $this->buildTree();

        if (! ($tree instanceof TreeNode)) {
            throw new Exception('Parsing error: cannot build AST, the trace is corrupted.', 1);
        }

        return $this->tree = $tree;
    }

    /**
     * Get root rule.
     *
     * @return  string
     */
    public function getRootRule()
    {
        foreach ($this->rules as $rule => $_) {
            if (! \is_int($rule)) {
                break;
            }
        }

        return $rule;
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

            if ($rule instanceof Ekzit) {
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
     * @param \Railt\Parser\Rule\Rule $zeRule Current rule.
     * @param int $next Next rule index.
     * @return bool
     */
    protected function parseCurrentRule(Rule $zeRule, $next)
    {
        if ($zeRule instanceof Token) {
            $name = $this->buffer->current()['token'];

            if ($zeRule->getTokenName() !== $name) {
                return false;
            }

            $value = $this->buffer->current()['value'];

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
                    } elseif ($trace instanceof Ekzit &&
                        $trace->isTransitional() === false) {
                        $skip += $trace->getDepth() > $this->depth;
                    }

                    if (0 < $skip) {
                        continue;
                    }

                    if ($trace instanceof Token &&
                        $unification === $trace->getUnificationIndex() &&
                        $value !== $trace->getValue()) {
                        return false;
                    }
                }
            }

            $current = $this->buffer->current();

            $namespace = $current['namespace'];
            $offset    = $current['offset'];

            $zzeRule = clone $zeRule;
            $zzeRule->setValue($value);
            $zzeRule->setOffset($offset);
            $zzeRule->setNamespace($namespace);

            if (isset($this->tokens[$namespace][$name])) {
                $zzeRule->setRepresentation($this->tokens[$namespace][$name]);
            } else {
                foreach ($this->tokens[$namespace] as $_name => $regex) {
                    if (($pos = \strpos($_name, ':')) === false) {
                        continue;
                    }

                    $_name = \substr($_name, 0, $pos);

                    if ($_name === $name) {
                        break;
                    }
                }

                $zzeRule->setRepresentation($regex);
            }

            \array_pop($this->todo);
            $this->trace[] = $zzeRule;
            $this->buffer->next();
            $this->errorToken = $this->buffer->current();

            return true;
        }
        if ($zeRule instanceof Concatenation) {
            if ($zeRule->isTransitional() === false) {
                ++$this->depth;
            }

            $this->trace[] = new Entry(
                $zeRule->getName(),
                0,
                null,
                $this->depth
            );
            $children      = $zeRule->getChildren();

            for ($i = \count($children) - 1; $i >= 0; --$i) {
                $nextRule     = $children[$i];
                $this->todo[] = new Ekzit($nextRule, 0);
                $this->todo[] = new Entry($nextRule, 0);
            }

            return true;
        }
        if ($zeRule instanceof Choice) {
            $children = $zeRule->getChildren();

            if ($next >= \count($children)) {
                return false;
            }

            if ($zeRule->isTransitional() === false) {
                ++$this->depth;
            }

            $this->trace[] = new Entry(
                $zeRule->getName(),
                $next,
                $this->todo,
                $this->depth
            );
            $nextRule      = $children[$next];
            $this->todo[]  = new Ekzit($nextRule, 0);
            $this->todo[]  = new Entry($nextRule, 0);

            return true;
        }
        if ($zeRule instanceof Repetition) {
            $nextRule = $zeRule->getChildren();

            if ($next === 0) {
                $name = $zeRule->getName();
                $min  = $zeRule->getMin();

                if ($zeRule->isTransitional() === false) {
                    ++$this->depth;
                }

                $this->trace[] = new Entry(
                    $name,
                    $min,
                    null,
                    $this->depth
                );
                \array_pop($this->todo);
                $this->todo[] = new Ekzit(
                    $name,
                    $min,
                    $this->todo
                );

                for ($i = 0; $i < $min; ++$i) {
                    $this->todo[] = new Ekzit($nextRule, 0);
                    $this->todo[] = new Entry($nextRule, 0);
                }

                return true;
            }
            $max = $zeRule->getMax();

            if ($max != -1 && $next > $max) {
                return false;
            }

            $this->todo[] = new Ekzit(
                $zeRule->getName(),
                $next,
                $this->todo
            );
            $this->todo[] = new Ekzit($nextRule, 0);
            $this->todo[] = new Entry($nextRule, 0);

            return true;
        }

        return false;
    }

    /**
     * Backtrack the trace.
     *
     * @return  bool
     */
    protected function backtrack()
    {
        $found = false;

        do {
            $last = \array_pop($this->trace);

            if ($last instanceof Entry) {
                $zeRule = $this->rules[$last->getRule()];
                $found  = $zeRule instanceof Choice;
            } elseif ($last instanceof Ekzit) {
                $zeRule = $this->rules[$last->getRule()];
                $found  = $zeRule instanceof Repetition;
            } elseif ($last instanceof Token) {
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
     * @return  \Railt\Parser\TreeNode
     */
    protected function buildTree($i = 0, &$children = [])
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
                if ($nextTrace instanceof Ekzit &&
                    $ruleName === $nextTrace->getRule()) {
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

                $handle   = [];
                $cId      = null;
                $cOptions = [];

                do {
                    $pop = \array_pop($children);

                    if (\is_object($pop) === true) {
                        $handle[] = $pop;
                    } elseif (\is_array($pop) === true && $cId === null) {
                        $cId      = $pop['id'];
                        $cOptions = $pop['options'];
                    } elseif ($ruleName === $pop) {
                        break;
                    }
                } while ($pop !== null);

                if ($cId === null) {
                    $cId      = $rule->getDefaultId();
                    $cOptions = $rule->getDefaultOptions();
                }

                if ($cId === null) {
                    for ($j = \count($handle) - 1; $j >= 0; --$j) {
                        $children[] = $handle[$j];
                    }

                    continue;
                }

                if (
                    \in_array('M', $cOptions, true) === true &&
                    $this->mergeTree($children, $handle, $cId) === true
                ) {
                    continue;
                }

                if (
                    \in_array('m', $cOptions, true) === true &&
                    $this->mergeTree($children, $handle, $cId, true) === true
                ) {
                    continue;
                }

                $cTree = new TreeNode($id ?: $cId);

                foreach ($handle as $child) {
                    $child->setParent($cTree);
                    $cTree->prependChild($child);
                }

                $children[] = $cTree;
            } elseif ($trace instanceof Ekzit) {
                return $i + 1;
            } else {
                if ($trace->isKept() === false) {
                    ++$i;

                    continue;
                }

                $child = new TreeNode('token', [
                    'token'     => $trace->getTokenName(),
                    'value'     => $trace->getValue(),
                    'namespace' => $trace->getNamespace(),
                    'offset'    => $trace->getOffset(),
                ]);

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
     * @param bool $recursive Whether we should merge recursively or
     *                                not.
     * @return  bool
     */
    protected function mergeTree(
        &$children,
        &$handle,
        $cId,
        $recursive = false
    )
    {
        \end($children);
        $last = \current($children);

        if (! \is_object($last)) {
            return false;
        }

        if ($cId !== $last->getId()) {
            return false;
        }

        if ($recursive === true) {
            foreach ($handle as $child) {
                $this->mergeTreeRecursive($last, $child);
            }

            return true;
        }

        foreach ($handle as $child) {
            $last->appendChild($child);
            $child->setParent($last);
        }

        return true;
    }

    /**
     * Merge recursively.
     * Please, see self::mergeTree() to know the context.
     *
     * @param \Railt\Parser\TreeNode $node Node that receives.
     * @param \Railt\Parser\TreeNode $newNode Node to merge.
     * @return  void
     */
    protected function mergeTreeRecursive(TreeNode $node, TreeNode $newNode): void
    {
        $nNId = $newNode->getId();

        if ($nNId === 'token') {
            $node->appendChild($newNode);
            $newNode->setParent($node);

            return;
        }

        $children = $node->getChildren();
        \end($children);
        $last = \current($children);

        if ($last->getId() !== $nNId) {
            $node->appendChild($newNode);
            $newNode->setParent($node);

            return;
        }

        foreach ($newNode->getChildren() as $child) {
            $this->mergeTreeRecursive($last, $child);
        }
    }

    /**
     * Get AST.
     *
     * @return  \Railt\Parser\TreeNode
     */
    public function getTree()
    {
        return $this->tree;
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
     * Get pragmas.
     *
     * @return  array
     */
    public function getPragmas()
    {
        return $this->pragmas;
    }

    /**
     * Get tokens.
     *
     * @return  array
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * Get the lexer iterator.
     *
     * @return Buffer
     */
    public function getTokenSequence(): Buffer
    {
        return $this->buffer;
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
