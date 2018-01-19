<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Runtime;

use Hoa\Iterator\Lookahead;
use Railt\Compiler\Exception\Exception;
use Railt\Compiler\Exception\RuleException;
use Railt\Compiler\FastLexer;
use Railt\Compiler\Lexer;
use Railt\Compiler\Rule\Choice;
use Railt\Compiler\Rule\Concatenation;
use Railt\Compiler\Rule\Repetition;
use Railt\Compiler\Rule\Token;

/**
 * Analyze rules and transform them into atomic rules operations.
 */
class Analyzer
{
    /**#@+
     * List of grammar token names.
     */
    private const T_SKIP         = 'skip';
    private const T_OR           = 'or';
    private const T_ZERO_OR_ONE  = 'zero_or_one';
    private const T_ONE_OR_MORE  = 'one_or_more';
    private const T_ZERO_OR_MORE = 'zero_or_more';
    private const T_N_TO_M       = 'n_to_m';
    private const T_ZERO_TO_M    = 'zero_to_m';
    private const T_N_OR_MORE    = 'n_or_more';
    private const T_EXACTLY_N    = 'exactly_n';
    private const T_SKIPPED      = 'skipped';
    private const T_KEPT         = 'kept';
    private const T_NAMED        = 'named';
    private const T_NODE         = 'node';
    private const T_GROUP_OPEN   = 'capturing_';
    private const T_GROUP_CLOSE  = '_capturing';
    /**#@-*/

    /**
     * PP lexemes.
     */
    protected const PP_LEXEMES = [
        Lexer\Token::T_DEFAULT_NAMESPACE => [
            self::T_SKIP         => ['\s', null, false],
            self::T_OR           => ['\|', null],
            self::T_ZERO_OR_ONE  => ['\?', null],
            self::T_ONE_OR_MORE  => ['\+', null],
            self::T_ZERO_OR_MORE => ['\*', null],
            self::T_N_TO_M       => ['\{[0-9]+,[0-9]+\}', null],
            self::T_ZERO_TO_M    => ['\{,[0-9]+\}', null],
            self::T_N_OR_MORE    => ['\{[0-9]+,\}', null],
            self::T_EXACTLY_N    => ['\{[0-9]+\}', null],
            self::T_SKIPPED      => ['::[a-zA-Z_][a-zA-Z0-9_]*(\[\d+\])?::', null],
            self::T_KEPT         => ['<[a-zA-Z_][a-zA-Z0-9_]*(\[\d+\])?>', null],
            self::T_NAMED        => ['[a-zA-Z_][a-zA-Z0-9_]*\(\)', null],
            self::T_NODE         => ['#[a-zA-Z_][a-zA-Z0-9_]*(:[mM])?', null],
            self::T_GROUP_OPEN   => ['\(', null],
            self::T_GROUP_CLOSE  => ['\)', null],
        ],
    ];

    /**
     * Lexer iterator.
     * @var Lookahead
     */
    protected $lexer;

    /**
     * Tokens representing rules.
     * @var array
     */
    protected $tokens;

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
     * Analyzer constructor.
     * @param array $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Build the analyzer of the rules (does not analyze the rules).
     *
     * @param array $rules
     * @return array
     * @throws \Railt\Compiler\Exception\LexerException
     * @throws \Railt\Compiler\Exception\InvalidPragmaException
     * @throws \Railt\Compiler\Exception\Exception
     * @throws RuleException
     */
    public function analyzeRules(array $rules): array
    {
        if (\count($rules) === 0) {
            throw new RuleException('No rules specified');
        }

        $this->parsedRules = [];
        $this->rules       = $rules;

        foreach ($rules as $key => $value) {
            $this->lexer = $this->getLookaheadIterator($value);
            $this->lexer->rewind();

            $this->ruleName = $key;
            $nodeId         = null;

            if ('#' === $key[0]) {
                $nodeId = $key;
                $key    = \substr($key, 1);
            }

            $pNodeId = $nodeId;
            $rule    = $this->rule($pNodeId);

            if ($rule === null) {
                throw new Exception('Error while parsing rule %s.', 1, $key);
            }

            $zeRule = $this->parsedRules[$rule];
            $zeRule->setName($key);
            $zeRule->setPPRepresentation($value);

            if ($nodeId !== null) {
                $zeRule->setDefaultId($nodeId);
            }

            unset($this->parsedRules[$rule]);
            $this->parsedRules[$key] = $zeRule;
        }

        return $this->parsedRules;
    }

    /**
     * @param string $rule
     * @return Lookahead
     * @throws \Railt\Compiler\Exception\LexerException
     * @throws \Railt\Compiler\Exception\InvalidPragmaException
     */
    private function getLookaheadIterator(string $rule): Lookahead
    {
        $lexer = new FastLexer($rule, static::PP_LEXEMES);

        return new Lookahead($lexer->getIterator());
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
            $this->parsedRules[$rule]->setNodeId($nNodeId);
        }

        $children[] = $rule;
        $others     = false;

        // … ( ::or:: concatenation() )*
        while ($this->lexer->current()[Lexer\Token::T_TOKEN] === self::T_OR) {
            $this->lexer->next();
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

        $name                     = $this->transitionalRuleCounter++;
        $this->parsedRules[$name] = new Concatenation($name, $children);

        return $name;
    }

    /**
     * Implementation of “repetition”.
     *
     * @param string|null $pNodeId
     * @return string|int|null
     * @throws \Railt\Compiler\Exception\Exception
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
        switch ($this->lexer->current()[Lexer\Token::T_TOKEN]) {
            case self::T_ZERO_OR_ONE:
                $min = 0;
                $max = 1;
                $this->lexer->next();
                break;

            case self::T_ONE_OR_MORE:
                $min = 1;
                $max = -1;
                $this->lexer->next();
                break;

            case self::T_ZERO_OR_MORE:
                $min = 0;
                $max = -1;
                $this->lexer->next();
                break;

            case self::T_N_TO_M:
                $handle = \trim($this->lexer->current()[Lexer\Token::T_VALUE], '{}');
                $nm     = \explode(',', $handle);
                $min    = (int)\trim($nm[0]);
                $max    = (int)\trim($nm[1]);
                $this->lexer->next();
                break;

            case self::T_ZERO_TO_M:
                $min = 0;
                $max = (int)\trim($this->lexer->current()[Lexer\Token::T_VALUE], '{,}');
                $this->lexer->next();
                break;

            case self::T_N_OR_MORE:
                $min = (int)\trim($this->lexer->current()[Lexer\Token::T_VALUE], '{,}');
                $max = -1;
                $this->lexer->next();
                break;

            case self::T_EXACTLY_N:
                $handle = \trim($this->lexer->current()[Lexer\Token::T_VALUE], '{}');
                $min    = (int)$handle;
                $max    = $min;
                $this->lexer->next();
                break;
        }

        // … <node>?
        if ($this->lexer->current()[Lexer\Token::T_TOKEN] === self::T_NODE) {
            $pNodeId = $this->lexer->current()[Lexer\Token::T_VALUE];
            $this->lexer->next();
        }

        if ($min === null) {
            return $children;
        }

        if ($max !== -1 && $max < $min) {
            $error = 'Upper bound %d must be greater or equal to lower bound %d in rule %s.';
            $error = \sprintf($error, $max, $min, $this->ruleName);
            throw new Exception($error, 2);
        }

        $name                     = $this->transitionalRuleCounter++;
        $this->parsedRules[$name] = new Repetition($name, $min, $max, $children, null);

        return $name;
    }

    /**
     * Implementation of “simple”.
     *
     * @param int|string|null $pNodeId
     * @return string|int|null
     * @throws \Railt\Compiler\Exception\Exception
     * @throws RuleException
     */
    protected function simple(&$pNodeId)
    {
        switch ($this->lexer->current()[Lexer\Token::T_TOKEN]) {
            case self::T_GROUP_OPEN:
                return $this->group($pNodeId);

            case self::T_SKIPPED:
                return $this->token(false);

            case self::T_KEPT:
                return $this->token();

            case self::T_NAMED:
                return $this->named();

            default:
                return null;
        }
    }

    /**
     * @return int|string|null
     * @throws \Railt\Compiler\Exception\RuleException
     */
    protected function named()
    {
        $tokenName = \rtrim($this->lexer->current()[Lexer\Token::T_VALUE], '()');

        $isEmptyRule = ! \array_key_exists($tokenName, $this->rules) &&
            ! \array_key_exists('#' . $tokenName, $this->rules);

        if ($isEmptyRule) {
            $error = \vsprintf('Cannot call rule %s() in rule %s because it does not exist.', [
                $tokenName,
                $this->ruleName,
            ]);

            throw new RuleException($error, 5);
        }

        if (
            $this->lexer->key() === 0 &&
            $this->lexer->getNext()[Lexer\Token::T_TOKEN] === Lexer\Token::T_EOF_NAME
        ) {
            $name                     = $this->transitionalRuleCounter++;
            $this->parsedRules[$name] = new Concatenation($name, [$tokenName]);
        } else {
            $name = $tokenName;
        }

        $this->lexer->next();

        return $name;
    }

    /**
     * @param bool $kept
     * @return int|string|null
     * @throws \Railt\Compiler\Exception\Exception
     */
    protected function token(bool $kept = true)
    {
        $tokenName = \trim($this->lexer->current()[Lexer\Token::T_VALUE], $kept ? '<>' : ':');

        if (\substr($tokenName, -1) === ']') {
            $uId       = (int)\substr($tokenName, \strpos($tokenName, '[') + 1, -1);
            $tokenName = \substr($tokenName, 0, \strpos($tokenName, '['));
        } else {
            $uId = -1;
        }

        $exists = false;

        foreach ($this->tokens as $namespace => $tokens) {
            foreach ((array)$tokens as $name => $token) {
                if ($name === $tokenName || \strpos($name, $tokenName) === 0) {
                    $exists = true;
                    break 2;
                }
            }
        }

        if ($exists === false) {
            $error = \sprintf('Token %s does not exist in rule %s.', $tokenName, $this->ruleName);
            throw new Exception($error, 4);
        }

        $name                     = $this->transitionalRuleCounter++;
        $this->parsedRules[$name] = new Token($name, $tokenName, null, $uId, $kept);
        $this->lexer->next();

        return $name;
    }

    /**
     * @param int|string|null $pNodeId
     * @return int|null|string
     */
    protected function group(&$pNodeId)
    {
        $this->lexer->next();
        $rule = $this->choice($pNodeId);

        if ($rule === null) {
            return null;
        }

        if ($this->lexer->current()[Lexer\Token::T_TOKEN] !== self::T_GROUP_CLOSE) {
            return null;
        }

        $this->lexer->next();

        return $rule;
    }
}
