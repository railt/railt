<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\Grammar\Lexer\Grammar as T;
use Railt\Compiler\Iterator\LookaheadIterator;
use Railt\Compiler\Lexer\Result\Eof;
use Railt\Compiler\LexerInterface;
use Railt\Compiler\Parser\Rule\Choice;
use Railt\Compiler\Parser\Rule\Concatenation;
use Railt\Compiler\Parser\Rule\Repetition;
use Railt\Compiler\Parser\Rule\Terminal as RuleToken;

/**
 * Analyze rules and transform them into atomic rules operations.
 *
 * TODO Rewrite sources
 */
class RuleAnalyzer
{
    /**
     * Lexer iterator.
     * @var LookaheadIterator
     */
    protected $lookahead;

    /**
     * Rules.
     * @var array
     */
    protected $rules;

    /**
     * Parsed rules.
     * @var array
     */
    protected $parsedRules;

    /**
     * Counter to auto-name transitional rules.
     * @var int
     */
    protected $transitionalRuleCounter = 0;

    /**
     * Rule name being analyzed.
     * @var string
     */
    private $ruleName;

    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * RuleAnalyzer constructor.
     * @param LexerInterface $lexer
     */
    public function __construct(LexerInterface $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * Build the analyzer of the rules (does not analyze the rules).
     *
     * @param iterable $rules
     * @return array
     */
    public function analyze(iterable $rules): array
    {
        if (\count($rules) === 0) {
            throw new \LogicException('No rules specified');
        }

        $this->parsedRules = [];
        $this->rules       = $rules;

        foreach ($rules as $key => $ruleTokens) {
            $this->lookahead = new LookaheadIterator($this->ruleTokens($ruleTokens));
            $this->lookahead->rewind();

            $this->ruleName = $key;
            $nodeId         = null;

            if ('#' === $key[0]) {
                $nodeId = $key;
                $key    = \substr($key, 1);
            }

            $pNodeId = $nodeId;
            $rule    = $this->rule($pNodeId);

            if ($rule === null) {
                throw new \LogicException(\sprintf('Error while parsing production "%s"', $key));
            }

            $zeRule = $this->parsedRules[$rule];
            $zeRule->setName($key);

            if ($nodeId !== null) {
                $zeRule->setDefaultId($nodeId);
            }

            unset($this->parsedRules[$rule]);
            $this->parsedRules[$key] = $zeRule;
        }

        return $this->parsedRules;
    }

    /**
     * @param array $rules
     * @return \Traversable|\Iterator
     */
    private function ruleTokens(array $rules): \Traversable
    {
        yield from $rules;
        yield new Eof(\end($rules)->offset() + \end($rules)->bytes());
    }

    /**
     * Implementation of "rule".
     *
     * @param string|null $pNodeId
     * @return string|int|null
     */
    protected function rule(&$pNodeId)
    {
        return $this->choice($pNodeId);
    }

    /**
     * Implementation of "choice".
     *
     * @param string|null $pNodeId
     * @return string|int|null
     */
    protected function choice(&$pNodeId)
    {
        $children = [];

        // concatenation() …
        $nNodeId = $pNodeId;
        $rule    = $this->concatenation($nNodeId);

        if ($rule === null) {
            return null;
        }

        if ($nNodeId !== null) {
            $this->parsedRules[$rule]->setNodeId($nNodeId);
        }

        $children[] = $rule;
        $others     = false;

        // … ( ::or:: concatenation() )*
        while ($this->lookahead->current()->name() === T::T_OR) {
            $this->lookahead->next();
            $others  = true;
            $nNodeId = $pNodeId;
            $rule    = $this->concatenation($nNodeId);

            if (null === $rule) {
                return null;
            }

            if (null !== $nNodeId) {
                $this->parsedRules[$rule]->setNodeId($nNodeId);
            }

            $children[] = $rule;
        }

        $pNodeId = null;

        if (false === $others) {
            return $rule;
        }

        $name                     = $this->transitionalRuleCounter++;
        $this->parsedRules[$name] = new Choice($name, $children);

        return $name;
    }

    /**
     * Implementation of "concatenation".
     *
     * @param string|null $pNodeId
     * @return string|int|null
     */
    protected function concatenation(&$pNodeId)
    {
        $children = [];

        // repetition() …
        $rule = $this->repetition($pNodeId);

        if ($rule === null) {
            return null;
        }

        $children[] = $rule;
        $others     = false;

        // … repetition()*
        while (null !== $r1 = $this->repetition($pNodeId)) {
            $children[] = $r1;
            $others     = true;
        }

        if ($others === false && $pNodeId === null) {
            return $rule;
        }

        $name                     = $this->transitionalRuleCounter++;
        $this->parsedRules[$name] = new Concatenation($name, $children);

        return $name;
    }

    /**
     * Implementation of "repetition".
     *
     * @param string|null $pNodeId
     * @return string|int|null
     */
    protected function repetition(&$pNodeId)
    {
        [$min, $max] = [null, null];

        // simple() …
        $children = $this->simple($pNodeId);

        if ($children === null) {
            return null;
        }

        // … quantifier()?
        switch ($this->lookahead->current()->name()) {
            case T::T_ZERO_OR_ONE:
                $min = 0;
                $max = 1;
                $this->lookahead->next();
                break;

            case T::T_ONE_OR_MORE:
                $min = 1;
                $max = -1;
                $this->lookahead->next();
                break;

            case T::T_ZERO_OR_MORE:
                $min = 0;
                $max = -1;
                $this->lookahead->next();
                break;

            case T::T_N_TO_M:
                $handle = \trim($this->lookahead->current()->value(), '{}');
                $nm     = \explode(',', $handle);
                $min    = (int)\trim($nm[0]);
                $max    = (int)\trim($nm[1]);
                $this->lookahead->next();
                break;

            case T::T_ZERO_TO_M:
                $min = 0;
                $max = (int)\trim($this->lookahead->current()->value(), '{,}');
                $this->lookahead->next();
                break;

            case T::T_N_OR_MORE:
                $min = (int)\trim($this->lookahead->current()->value(), '{,}');
                $max = -1;
                $this->lookahead->next();
                break;

            case T::T_EXACTLY_N:
                $handle = \trim($this->lookahead->current()->value(), '{}');
                $min    = (int)$handle;
                $max    = $min;
                $this->lookahead->next();
                break;
        }

        // … <node>?
        if ($this->lookahead->current()->name() === T::T_NODE) {
            $pNodeId = $this->lookahead->current()->value();
            $this->lookahead->next();
        }

        if ($min === null) {
            return $children;
        }

        if ($max !== -1 && $max < $min) {
            $error = 'Upper bound %d must be greater or equal to lower bound %d in rule %s.';
            $error = \sprintf($error, $max, $min, $this->ruleName);
            throw new \LogicException($error, 2);
        }

        $name                     = $this->transitionalRuleCounter++;
        $this->parsedRules[$name] = new Repetition($name, $min, $max, $children, null);

        return $name;
    }

    /**
     * Implementation of "simple".
     *
     * @param int|string|null $pNodeId
     * @return string|int|null
     */
    protected function simple(&$pNodeId)
    {
        switch ($this->lookahead->current()->name()) {
            case T::T_GROUP_OPEN:
                return $this->group($pNodeId);

            case T::T_SKIPPED:
                return $this->token(false);

            case T::T_KEPT:
                return $this->token();

            case T::T_NAMED:
                return $this->named();

            default:
                return null;
        }
    }

    /**
     * @param int|string|null $pNodeId
     * @return int|null|string
     */
    protected function group(&$pNodeId)
    {
        $this->lookahead->next();
        $rule = $this->choice($pNodeId);

        if ($rule === null) {
            return null;
        }

        if ($this->lookahead->current()->name() !== T::T_GROUP_CLOSE) {
            return null;
        }

        $this->lookahead->next();

        return $rule;
    }

    /**
     * @param bool $kept
     * @return int|string|null
     */
    protected function token(bool $kept = true)
    {
        $tokenName = \trim($this->lookahead->current()->value(), $kept ? '<>' : ':');

        if (\substr($tokenName, -1) === ']') {
            $uId       = (int)\substr($tokenName, \strpos($tokenName, '[') + 1, -1);
            $tokenName = \substr($tokenName, 0, \strpos($tokenName, '['));
        } else {
            $uId = -1;
        }

        if (! $this->lexer->has($tokenName)) {
            $error = \sprintf('Token %s does not exist in rule %s.', $tokenName, $this->ruleName);
            throw new \LogicException($error, 4);
        }

        $name                     = $this->transitionalRuleCounter++;
        $this->parsedRules[$name] = new RuleToken($name, $tokenName, null, $uId, $kept);
        $this->lookahead->next();

        return $name;
    }

    /**
     * @return int|string|null
     */
    protected function named()
    {
        $tokenName = \rtrim($this->lookahead->current()->value(), '()');

        $isEmptyRule = ! \array_key_exists($tokenName, $this->rules) &&
            ! \array_key_exists('#' . $tokenName, $this->rules);

        if ($isEmptyRule) {
            $error = \vsprintf('Cannot call rule %s() in rule %s because it does not exist.', [
                $tokenName,
                $this->ruleName,
            ]);

            throw new \LogicException($error, 5);
        }

        if (
            $this->lookahead->key() === 0 &&
            $this->lookahead->getNext()->isEof()
        ) {
            $name                     = $this->transitionalRuleCounter++;
            $this->parsedRules[$name] = new Concatenation($name, [$tokenName]);
        } else {
            $name = $tokenName;
        }

        $this->lookahead->next();

        return $name;
    }
}
