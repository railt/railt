<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader\Analyzer;

use Railt\Compiler\Exception\GrammarException;
use Railt\Compiler\Grammar\Builder\AlternationBuilder;
use Railt\Compiler\Grammar\Builder\Buildable;
use Railt\Compiler\Grammar\Builder\ConcatenationBuilder;
use Railt\Compiler\Grammar\Builder\Movable;
use Railt\Compiler\Grammar\Builder\RepetitionBuilder;
use Railt\Compiler\Grammar\Builder\TokenBuilder;
use Railt\Compiler\Grammar\Lexer\Grammar as T;
use Railt\Compiler\Iterator\LookaheadIterator;
use Railt\Compiler\Lexer\Result\Eoi;
use Railt\Compiler\TokenInterface;

/**
 * Analyze rules and transform them into atomic rules operations.
 */
class GrammarAnalyzer extends BaseAnalyzer
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
     * Build the analyzer of the rules (does not analyze the rules).
     *
     * @param iterable $rules
     * @return array
     * @throws GrammarException
     */
    public function analyze(iterable $rules): array
    {
        if (\count($rules) === 0) {
            throw new GrammarException('No rules specified');
        }

        $this->parsedRules = [];
        $this->rules       = $rules;

        foreach ($rules as $key => $value) {
            $this->lookahead = new LookaheadIterator($this->rules($value));
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
                throw new GrammarException(\sprintf('Error while parsing rule %s.', $key), 1);
            }

            /** @var Buildable|Movable $symbol */
            $symbol = $this->parsedRules[$rule];
            $symbol->move($key);

            unset($this->parsedRules[$rule]);
            $this->parsedRules[$key] = $symbol;
        }

        return $this->parsedRules;
    }

    /**
     * @param array $rules
     * @return \Generator|\Traversable
     */
    private function rules(array $rules): \Traversable
    {
        yield from $rules;
        yield new Eoi(0);
    }

    /**
     * Implementation of “rule”.
     *
     * @param string|null $pNodeId
     * @return string|int|null
     */
    protected function rule(&$pNodeId)
    {
        return $this->choice($pNodeId);
    }

    /**
     * Implementation of “choice”.
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
            $this->parsedRules[$rule]->rename($nNodeId);
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
                $this->parsedRules[$rule]->rename($nNodeId);
            }

            $children[] = $rule;
        }

        $pNodeId = null;

        if (false === $others) {
            return $rule;
        }

        $name = $this->transitionalRuleCounter++;

        $this->parsedRules[$name] = new AlternationBuilder($name, $children);

        return $name;
    }

    /**
     * Implementation of “concatenation”.
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

        $name = $this->transitionalRuleCounter++;

        $this->parsedRules[$name] = new ConcatenationBuilder($name, $children);

        return $name;
    }

    /**
     * Implementation of “repetition”.
     *
     * @param string|null $pNodeId
     * @return string|int|null
     * @throws GrammarException
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
            throw new GrammarException($error, 2);
        }

        $name = $this->transitionalRuleCounter++;

        $this->parsedRules[$name] = new RepetitionBuilder($name, $min, $max, (array)$children);

        return $name;
    }

    /**
     * Implementation of “simple”.
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
     * @return int|string|null
     * @throws GrammarException
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

            throw new GrammarException($error, 5);
        }

        if (
            $this->lookahead->key() === 0 &&
            $this->lookahead->getNext()->name() === TokenInterface::END_OF_INPUT
        ) {
            $name                     = $this->transitionalRuleCounter++;

            $this->parsedRules[$name] = new ConcatenationBuilder($name, (array)$tokenName);
        } else {
            $name = $tokenName;
        }

        $this->lookahead->next();

        return $name;
    }

    /**
     * @param bool $kept
     * @return int|string|null
     * @throws GrammarException
     */
    protected function token(bool $kept = true)
    {
        $tokenName = \trim($this->lookahead->current()->value(), $kept ? '<>' : ':');

        if (! $this->getLexer()->has($tokenName)) {
            $error = \sprintf('Token %s does not exist in rule %s.', $tokenName, $this->ruleName);
            throw new GrammarException($error, 4);
        }

        $name = $this->transitionalRuleCounter++;

        $this->parsedRules[$name] = new TokenBuilder($name, $tokenName, $kept);
        $this->lookahead->next();

        return $name;
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
}
