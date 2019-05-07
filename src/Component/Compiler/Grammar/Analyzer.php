<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Compiler\Grammar;

use Railt\Component\Compiler\Exception\GrammarException;
use Railt\Component\Compiler\Grammar\Builder\AbstractBuilder;
use Railt\Component\Compiler\Grammar\Builder\Alternation;
use Railt\Component\Compiler\Grammar\Builder\Concatenation;
use Railt\Component\Compiler\Grammar\Builder\Repetition;
use Railt\Component\Compiler\Grammar\Builder\Terminal;
use Railt\Component\Compiler\Grammar\Delegate\RuleDelegate;
use Railt\Component\Lexer\Token\EndOfInput;
use Railt\Component\Parser\Rule\Rule;

/**
 * Analyze rules and transform them into atomic rules operations.
 */
class Analyzer
{
    /**
     * @var array|RuleDelegate[]
     */
    protected $rules = [];

    /**
     * Parsed rules.
     *
     * @var array|AbstractBuilder[]
     */
    protected $parsedRules;

    /**
     * Counter to auto-name transitional rules.
     *
     * @var int
     */
    protected $transitionalRuleCounter = 0;

    /**
     * Rule name being analyzed.
     *
     * @var string
     */
    private $ruleName;

    /**
     * @param RuleDelegate $delegate
     */
    public function addRuleDelegate(RuleDelegate $delegate): void
    {
        $this->rules[$delegate->getRuleName()] = $delegate;
    }

    /**
     * Build the analyzer of the rules (does not analyze the rules).
     *
     * @return Rule[]|\Traversable
     * @throws GrammarException
     */
    public function analyze(): iterable
    {
        if (\count($this->rules) === 0) {
            throw new GrammarException('No rules specified');
        }

        $this->parsedRules = [];

        foreach ($this->rules as $delegate) {
            $this->ruleName = $delegate->getRuleName();
            $nodeId = $delegate->isKept()
                ? $delegate->getRuleName()
                : null;

            $pNodeId = $nodeId;
            $rule = $this->rule($delegate->getInnerTokens(), $pNodeId);

            if ($rule === null) {
                $error = \sprintf('Error while parsing rule %s.', $delegate->getRuleName());
                throw new GrammarException($error, 1);
            }

            $zeRule = $this->parsedRules[$rule];
            $zeRule->setName($delegate->getRuleName());

            if ($nodeId !== null) {
                $zeRule->setDefaultId($nodeId);
            }

            unset($this->parsedRules[$rule]);
            $this->parsedRules[$delegate->getRuleName()] = $zeRule;
        }

        foreach ($this->parsedRules as $builder) {
            yield $builder->build();
        }
    }

    /**
     * Implementation of “rule”.
     *
     * @param LookaheadIterator $tokens
     * @param string|null $pNodeId
     * @return string|int|null
     * @throws GrammarException
     */
    protected function rule(LookaheadIterator $tokens, &$pNodeId)
    {
        return $this->choice($tokens, $pNodeId);
    }

    /**
     * Implementation of “choice”.
     *
     * @param LookaheadIterator $tokens
     * @param string|null $pNodeId
     * @return string|int|null
     * @throws GrammarException
     */
    protected function choice(LookaheadIterator $tokens, &$pNodeId)
    {
        $children = [];

        // concatenation() …
        $nNodeId = $pNodeId;
        $rule = $this->concatenation($tokens, $nNodeId);

        if ($rule === null) {
            return null;
        }

        if ($nNodeId !== null) {
            $this->parsedRules[$rule]->setNodeId($nNodeId);
        }

        $children[] = $rule;
        $others = false;

        // … ( ::or:: concatenation() )*
        while ($tokens->current()->getName() === Parser::T_OR) {
            $tokens->next();
            $others = true;
            $nNodeId = $pNodeId;
            $rule = $this->concatenation($tokens, $nNodeId);

            if ($rule === null) {
                return null;
            }

            if ($nNodeId !== null) {
                $this->parsedRules[$rule]->setNodeId($nNodeId);
            }

            $children[] = $rule;
        }

        $pNodeId = null;

        if ($others === false) {
            return $rule;
        }

        $name = $this->transitionalRuleCounter++;

        $this->parsedRules[$name] = new Alternation($name, $children);

        return $name;
    }

    /**
     * Implementation of “concatenation”.
     *
     * @param LookaheadIterator $tokens
     * @param string|null $pNodeId
     * @return string|int|null
     * @throws GrammarException
     */
    protected function concatenation(LookaheadIterator $tokens, &$pNodeId)
    {
        $children = [];

        // repetition() …
        $rule = $this->repetition($tokens, $pNodeId);

        if ($rule === null) {
            return null;
        }

        $children[] = $rule;
        $others = false;

        // … repetition()*
        while (null !== $r1 = $this->repetition($tokens, $pNodeId)) {
            $children[] = $r1;
            $others = true;
        }

        if ($others === false && $pNodeId === null) {
            return $rule;
        }

        $name = $this->transitionalRuleCounter++;

        $this->parsedRules[$name] = new Concatenation($name, $children);

        return $name;
    }

    /**
     * Implementation of “repetition”.
     *
     * @param LookaheadIterator $tokens
     * @param string|null $pNodeId
     * @return string|int|null
     * @throws GrammarException
     */
    protected function repetition(LookaheadIterator $tokens, &$pNodeId)
    {
        [$min, $max] = [null, null];

        // simple() …
        $children = $this->simple($tokens, $pNodeId);

        if ($children === null) {
            return null;
        }

        // … quantifier()?
        switch ($tokens->current()->getName()) {
            case Parser::T_REPEAT_ZERO_OR_ONE:
                [$min, $max] = [0, 1];
                $tokens->next();
                break;

            case Parser::T_REPEAT_ONE_OR_MORE:
                [$min, $max] = [1, -1];
                $tokens->next();
                break;

            case Parser::T_REPEAT_ZERO_OR_MORE:
                [$min, $max] = [0, -1];
                $tokens->next();
                break;

            case Parser::T_REPEAT_N_TO_M:
                $min = (int)$tokens->current()->getValue(1);
                $max = (int)$tokens->current()->getValue(2);
                $tokens->next();
                break;

            case Parser::T_REPEAT_ZERO_TO_M:
                [$min, $max] = [0, (int)$tokens->current()->getValue(1)];
                $tokens->next();
                break;

            case Parser::T_REPEAT_N_OR_MORE:
                [$min, $max] = [(int)$tokens->current()->getValue(1), -1];
                $tokens->next();
                break;

            case Parser::T_REPEAT_EXACTLY_N:
                $min = $max = (int)$tokens->current()->getValue(1);
                $tokens->next();
                break;
        }

        // … <node>?
        if ($tokens->current()->getName() === Parser::T_KEPT_NAME) {
            $tokens->next();
            $pNodeId = $tokens->current()->getValue();
            $tokens->next();
        }

        if ($min === null) {
            return $children;
        }

        if ($max !== -1 && $max < $min) {
            $error = 'Upper bound %d must be greater or equal to lower bound %d in rule %s.';
            $error = \sprintf($error, $max, $min, $this->ruleName);
            throw new GrammarException($error, 2);
        }

        $name = $this->transitionalRuleCounter++;

        $this->parsedRules[$name] = new Repetition($name, $min, $max, $children);

        return $name;
    }

    /**
     * Implementation of “simple”.
     *
     * @param LookaheadIterator $tokens
     * @param int|string|null $pNodeId
     * @return string|int|null
     * @throws GrammarException
     */
    protected function simple(LookaheadIterator $tokens, &$pNodeId)
    {
        switch ($tokens->current()->getName()) {
            case Parser::T_GROUP_OPEN:
                return $this->group($tokens, $pNodeId);

            case Parser::T_TOKEN_SKIPPED:
                return $this->token($tokens, false);

            case Parser::T_TOKEN_KEPT:
                return $this->token($tokens, true);

            case Parser::T_INVOKE:
                return $this->invoke($tokens);

            default:
                return null;
        }
    }

    /**
     * @param LookaheadIterator $tokens
     * @param int|string|null $pNodeId
     * @return int|null|string
     * @throws GrammarException
     */
    protected function group(LookaheadIterator $tokens, &$pNodeId)
    {
        $tokens->next();
        $rule = $this->choice($tokens, $pNodeId);

        if ($rule === null) {
            return null;
        }

        if ($tokens->current()->getName() !== Parser::T_GROUP_CLOSE) {
            return null;
        }

        $tokens->next();

        return $rule;
    }

    /**
     * @param LookaheadIterator $tokens
     * @param bool $kept
     * @return int|string|null
     */
    protected function token(LookaheadIterator $tokens, bool $kept = true)
    {
        $tokenName = $tokens->current()->getValue(1);

        $name = $this->transitionalRuleCounter++;

        $this->parsedRules[$name] = new Terminal($name, $tokenName, $kept);
        $tokens->next();

        return $name;
    }

    /**
     * @param LookaheadIterator $tokens
     * @return int|string
     * @throws GrammarException
     */
    protected function invoke(LookaheadIterator $tokens)
    {
        $tokenName = $tokens->current()->getValue(1);

        if (! \array_key_exists($tokenName, $this->rules)) {
            $error = \vsprintf('Cannot call rule %s() in rule %s because it does not exist.', [
                $tokenName,
                $this->ruleName,
            ]);

            throw new GrammarException($error, 5);
        }

        if ($tokens->getNext()->getName() === EndOfInput::T_NAME) {
            $name = $this->transitionalRuleCounter++;
            $this->parsedRules[$name] = new Concatenation($name, [$tokenName]);
        } else {
            $name = $tokenName;
        }

        $tokens->next();

        return $name;
    }
}
