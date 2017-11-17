<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Hoa\Compiler\Llk;

use Hoa\Compiler;
use Hoa\Compiler\Exception;
use Hoa\Compiler\Llk\Rule\Choice;
use Hoa\Compiler\Llk\Rule\Concatenation;
use Hoa\Compiler\Llk\Rule\Ekzit;
use Hoa\Compiler\Llk\Rule\Entry;
use Hoa\Compiler\Llk\Rule\Repetition;
use Hoa\Compiler\Llk\Rule\Rule;
use Hoa\Compiler\Llk\Rule\Token;
use Hoa\Iterator;

/**
 * Class \Hoa\Compiler\Llk\Parser.
 *
 * LL(k) parser.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Parser
{
    /**
     * List of pragmas.
     *
     * @var array
     */
    protected $_pragmas;

    /**
     * List of skipped tokens.
     *
     * @var array
     */
    protected $_skip;

    /**
     * Associative array (token name => token regex), to be defined in
     * precedence order.
     *
     * @var array
     */
    protected $_tokens;

    /**
     * Rules, to be defined as associative array, name => Rule object.
     *
     * @var array
     */
    protected $_rules;

    /**
     * Lexer iterator.
     *
     * @var \Hoa\Iterator\Lookahead
     */
    protected $_tokenSequence;

    /**
     * Possible token causing an error.
     *
     * @var array
     */
    protected $_errorToken;

    /**
     * Trace of activated rules.
     *
     * @var array
     */
    protected $_trace = [];

    /**
     * Stack of todo list.
     *
     * @var array
     */
    protected $_todo;

    /**
     * AST.
     *
     * @var \Hoa\Compiler\Llk\TreeNode
     */
    protected $_tree;

    /**
     * Current depth while building the trace.
     *
     * @var int
     */
    protected $_depth = -1;

    /**
     * Construct the parser.
     *
     * @param   array $tokens Tokens.
     * @param   array $rules Rules.
     * @param   array $pragmas Pragmas.
     */
    public function __construct(
        array $tokens = [],
        array $rules = [],
        array $pragmas = []
    ) {
        $this->_tokens  = $tokens;
        $this->_rules   = $rules;
        $this->_pragmas = $pragmas;
    }

    /**
     * Parse :-).
     *
     * @param   string $text Text to parse.
     * @param   string $rule The axiom, i.e. root rule.
     * @param   bool $tree Whether build tree or not.
     * @return  mixed
     * @throws  \Hoa\Compiler\Exception\UnexpectedToken
     */
    public function parse($text, $rule = null, $tree = true)
    {
        $k = 1024;

        if (isset($this->_pragmas['parser.lookahead'])) {
            $k = \max(0, (int)$this->_pragmas['parser.lookahead']);
        }

        $lexer                = new Lexer($this->_pragmas);
        $this->_tokenSequence = new Iterator\Buffer(
            $lexer->lexMe($text, $this->_tokens),
            $k
        );
        $this->_tokenSequence->rewind();

        $this->_errorToken = null;
        $this->_trace      = [];
        $this->_todo       = [];

        if (\array_key_exists($rule, $this->_rules) === false) {
            $rule = $this->getRootRule();
        }

        $closeRule   = new Ekzit($rule, 0);
        $openRule    = new Entry($rule, 0, [$closeRule]);
        $this->_todo = [$closeRule, $openRule];

        do {
            $out = $this->unfold();

            if ($out !== null &&
                $this->_tokenSequence->current()['token'] === 'EOF') {
                break;
            }

            if ($this->backtrack() === false) {
                $token = $this->_errorToken;

                if ($this->_errorToken === null) {
                    $token = $this->_tokenSequence->current();
                }

                $error = \vsprintf('Unexpected token "%s" (%s)', [
                    $token['value'],
                    $token['token'],
                ]);

                throw Compiler\Exception\UnexpectedToken::fromOffset($error, $text, $token['offset']);
            }
        } while (true);

        if ($tree === false) {
            return true;
        }

        $tree = $this->_buildTree();

        if (! ($tree instanceof TreeNode)) {
            throw new Exception('Parsing error: cannot build AST, the trace is corrupted.', 1);
        }

        return $this->_tree = $tree;
    }

    /**
     * Get root rule.
     *
     * @return  string
     */
    public function getRootRule()
    {
        foreach ($this->_rules as $rule => $_) {
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
        while (0 < \count($this->_todo)) {
            $rule = \array_pop($this->_todo);

            if ($rule instanceof Ekzit) {
                $rule->setDepth($this->_depth);
                $this->_trace[] = $rule;

                if ($rule->isTransitional() === false) {
                    --$this->_depth;
                }
            } else {
                $ruleName = $rule->getRule();
                $next     = $rule->getData();
                $zeRule   = $this->_rules[$ruleName];
                $out      = $this->_parse($zeRule, $next);

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
     * @param \Hoa\Compiler\Llk\Rule\Rule $zeRule Current rule.
     * @param int $next Next rule index.
     * @return bool
     */
    protected function _parse(Rule $zeRule, $next)
    {
        if ($zeRule instanceof Token) {
            $name = $this->_tokenSequence->current()['token'];

            if ($zeRule->getTokenName() !== $name) {
                return false;
            }

            $value = $this->_tokenSequence->current()['value'];

            if (0 <= $unification = $zeRule->getUnificationIndex()) {
                for ($skip = 0, $i = \count($this->_trace) - 1; $i >= 0; --$i) {
                    $trace = $this->_trace[$i];

                    if ($trace instanceof Entry) {
                        if ($trace->isTransitional() === false) {
                            if ($trace->getDepth() <= $this->_depth) {
                                break;
                            }

                            --$skip;
                        }
                    } elseif ($trace instanceof Ekzit &&
                        $trace->isTransitional() === false) {
                        $skip += $trace->getDepth() > $this->_depth;
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

            $current = $this->_tokenSequence->current();

            $namespace = $current['namespace'];
            $offset    = $current['offset'];

            $zzeRule = clone $zeRule;
            $zzeRule->setValue($value);
            $zzeRule->setOffset($offset);
            $zzeRule->setNamespace($namespace);

            if (isset($this->_tokens[$namespace][$name])) {
                $zzeRule->setRepresentation($this->_tokens[$namespace][$name]);
            } else {
                foreach ($this->_tokens[$namespace] as $_name => $regex) {
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

            \array_pop($this->_todo);
            $this->_trace[] = $zzeRule;
            $this->_tokenSequence->next();
            $this->_errorToken = $this->_tokenSequence->current();

            return true;
        }
        if ($zeRule instanceof Concatenation) {
            if ($zeRule->isTransitional() === false) {
                ++$this->_depth;
            }

            $this->_trace[] = new Entry(
                $zeRule->getName(),
                0,
                null,
                $this->_depth
            );
            $children       = $zeRule->getChildren();

            for ($i = \count($children) - 1; $i >= 0; --$i) {
                $nextRule      = $children[$i];
                $this->_todo[] = new Ekzit($nextRule, 0);
                $this->_todo[] = new Entry($nextRule, 0);
            }

            return true;
        }
        if ($zeRule instanceof Choice) {
            $children = $zeRule->getChildren();

            if ($next >= \count($children)) {
                return false;
            }

            if ($zeRule->isTransitional() === false) {
                ++$this->_depth;
            }

            $this->_trace[] = new Entry(
                $zeRule->getName(),
                $next,
                $this->_todo,
                $this->_depth
            );
            $nextRule       = $children[$next];
            $this->_todo[]  = new Ekzit($nextRule, 0);
            $this->_todo[]  = new Entry($nextRule, 0);

            return true;
        }
        if ($zeRule instanceof Repetition) {
            $nextRule = $zeRule->getChildren();

            if ($next === 0) {
                $name = $zeRule->getName();
                $min  = $zeRule->getMin();

                if ($zeRule->isTransitional() === false) {
                    ++$this->_depth;
                }

                $this->_trace[] = new Entry(
                    $name,
                    $min,
                    null,
                    $this->_depth
                );
                \array_pop($this->_todo);
                $this->_todo[] = new Ekzit(
                    $name,
                    $min,
                    $this->_todo
                );

                for ($i = 0; $i < $min; ++$i) {
                    $this->_todo[] = new Ekzit($nextRule, 0);
                    $this->_todo[] = new Entry($nextRule, 0);
                }

                return true;
            }
            $max = $zeRule->getMax();

            if ($max != -1 && $next > $max) {
                return false;
            }

            $this->_todo[] = new Ekzit(
                    $zeRule->getName(),
                    $next,
                    $this->_todo
                );
            $this->_todo[] = new Ekzit($nextRule, 0);
            $this->_todo[] = new Entry($nextRule, 0);

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
            $last = \array_pop($this->_trace);

            if ($last instanceof Entry) {
                $zeRule = $this->_rules[$last->getRule()];
                $found  = $zeRule instanceof Choice;
            } elseif ($last instanceof Ekzit) {
                $zeRule = $this->_rules[$last->getRule()];
                $found  = $zeRule instanceof Repetition;
            } elseif ($last instanceof Token) {
                $this->_tokenSequence->previous();

                if ($this->_tokenSequence->valid() === false) {
                    return false;
                }
            }
        } while (0 < \count($this->_trace) && $found === false);

        if ($found === false) {
            return false;
        }

        $rule          = $last->getRule();
        $next          = $last->getData() + 1;
        $this->_depth  = $last->getDepth();
        $this->_todo   = $last->getTodo();
        $this->_todo[] = new Entry($rule, $next);

        return true;
    }

    /**
     * Build AST from trace.
     * Walk through the trace iteratively and recursively.
     *
     * @param   int $i Current trace index.
     * @param   array &$children Collected children.
     * @return  \Hoa\Compiler\Llk\TreeNode
     */
    protected function _buildTree($i = 0, &$children = [])
    {
        $max = \count($this->_trace);

        while ($i < $max) {
            $trace = $this->_trace[$i];

            if ($trace instanceof Entry) {
                $ruleName  = $trace->getRule();
                $rule      = $this->_rules[$ruleName];
                $isRule    = $trace->isTransitional() === false;
                $nextTrace = $this->_trace[$i + 1];
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

                $i = $this->_buildTree($i + 1, $children);

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
     * @param   array &$children Current children being gathering.
     * @param   array &$handle Children of the new node.
     * @param   string $cId Node ID.
     * @param   bool $recursive Whether we should merge recursively or
     *                                not.
     * @return  bool
     */
    protected function mergeTree(
        &$children,
        &$handle,
        $cId,
        $recursive = false
    ) {
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
     * @param   \Hoa\Compiler\Llk\TreeNode $node Node that receives.
     * @param   \Hoa\Compiler\Llk\TreeNode $newNode Node to merge.
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
     * @return  \Hoa\Compiler\Llk\TreeNode
     */
    public function getTree()
    {
        return $this->_tree;
    }

    /**
     * Get trace.
     *
     * @return  array
     */
    public function getTrace()
    {
        return $this->_trace;
    }

    /**
     * Get pragmas.
     *
     * @return  array
     */
    public function getPragmas()
    {
        return $this->_pragmas;
    }

    /**
     * Get tokens.
     *
     * @return  array
     */
    public function getTokens()
    {
        return $this->_tokens;
    }

    /**
     * Get the lexer iterator.
     *
     * @return  \Hoa\Iterator\Buffer
     */
    public function getTokenSequence()
    {
        return $this->_tokenSequence;
    }

    /**
     * Get rule by name.
     *
     * @param $name
     * @return Rule|null
     */
    public function getRule($name): ?Rule
    {
        return $this->_rules[$name] ?? null;
    }

    /**
     * Get rules.
     *
     * @return array
     */
    public function getRules(): array
    {
        return $this->_rules;
    }
}
