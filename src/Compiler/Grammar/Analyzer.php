<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar;

use Railt\Compiler\Grammar\Analyzer\Context;
use Railt\Compiler\Grammar\Exceptions\InvalidRuleException;
use Railt\Compiler\Grammar\Reader\ParsingState;
use Railt\Lexer\Tokens\Eof;
use Railt\Lexer\Tokens\Output;
use Railt\Runtime\Rule\Rule;

/**
 * Class Analyser
 */
class Analyzer
{
    /**
     * @var array
     */
    private $rules;

    /**
     * @var array
     */
    private $tokens;

    /**
     * @var array|Rule[]
     */
    private $parsedRules = [];

    /**
     * Analyser constructor.
     * @param array $tokens
     * @param array $rules
     */
    public function __construct(array $tokens, array $rules)
    {
        $this->rules  = $rules;
        $this->tokens = $tokens;
    }

    /**
     * @return array
     * @throws InvalidRuleException
     */
    public function getRules(): array
    {
        $this->parsedRules = [];

        return $this->analyze();
    }

    /**
     * @return array
     * @throws InvalidRuleException
     */
    public function analyze(): array
    {
        foreach ($this->getRulesIterator() as $fqn => $rules) {
            (new Context($this, $fqn, $rules))->reduce();
        }

        return [];
    }

    /**
     * @param string $rule
     * @return bool
     */
    public function hasRule(string $rule): bool
    {
        return \array_key_exists($rule, $this->rules) || \array_key_exists('#' . $rule, $this->rules);
    }

    /**
     * @param string $rule
     * @return array
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidRuleException
     */
    public function getRule(string $rule): array
    {
        if (\array_key_exists($rule, $this->rules)) {
            return $this->rules[$rule];
        }

        if (\array_key_exists('#' . $rule, $this->rules)) {
            return $this->rules['#' . $rule];
        }

        throw new InvalidRuleException(\sprintf('The production rule "%s" is not defined', $rule));
    }

    /**
     * @return \Generator
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidRuleException
     */
    private function getRulesIterator(): \Traversable
    {
        foreach ($this->rules as $name => $rule) {
            /** @var \SplQueue $stack */
            $stack = $rule[ParsingState::I_RULE_BODY];

            if ($stack->count() === 0) {
                $error = \sprintf('The production rule "%s" can not be empty', $name);
                throw $this->ruleError($error, $rule);
            }

            $stack->push(Eof::create($this->getRuleEof($stack)));

            yield $name => $rule;
        }
    }

    /**
     * @param \SplQueue $stack
     * @return int
     */
    private function getRuleEof(\SplQueue $stack): int
    {
        return $stack[$stack->count() - 1][Output::I_TOKEN_OFFSET];
    }

    /**
     * @param string $message
     * @param array $rule
     * @return InvalidRuleException
     */
    private function ruleError(string $message, array $rule): InvalidRuleException
    {
        [$file, $offset] = [$rule[ParsingState::I_RULE_FILE], $rule[ParsingState::I_RULE_OFFSET]];

        return InvalidRuleException::fromFile($message, $file, $file->getPosition($offset));
    }
}
