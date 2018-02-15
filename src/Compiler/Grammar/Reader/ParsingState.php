<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\Grammar\Analyzer;
use Railt\Compiler\Grammar\Exceptions\GrammarException;
use Railt\Compiler\Grammar\Exceptions\InvalidRuleException;
use Railt\Compiler\Grammar\Lexer;
use Railt\Io\Readable;
use Railt\Lexer\Tokens\Output;

/**
 * Class ParsingState
 */
class ParsingState implements State
{
    /**@#+
     * Rule body info
     */
    public const I_RULE_FILE = 0x00;
    public const I_RULE_OFFSET = 0x01;
    public const I_RULE_BODY = 0x02;
    /**@#-*/

    /**
     * @var array
     */
    private $rules = [];

    /**
     * @var string|null
     */
    private $lastRule;

    /**
     * @var LexingState
     */
    private $tokens;

    /**
     * ParsingState constructor.
     * @param LexingState $tokens
     */
    public function __construct(LexingState $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @param Readable $grammar
     * @param array $rule
     * @throws \Railt\Compiler\Grammar\Exceptions\GrammarException
     */
    public function resolve(Readable $grammar, array $rule): void
    {
        if ($rule[Output::I_TOKEN_NAME] === Lexer::T_NODE_DEFINITION) {
            $this->createRule($grammar, $rule);

            return;
        }

        $this->parseRule($grammar, $rule);
    }

    /**
     * @param Readable $grammar
     * @param array $rule
     */
    private function createRule(Readable $grammar, array $rule): void
    {
        $this->lastRule = \trim($rule[Output::I_TOKEN_BODY], " :\t\n\r\0\x0B");

        $this->rules[$this->lastRule] = [
            self::I_RULE_FILE   => $grammar,
            self::I_RULE_OFFSET => $rule[Output::I_TOKEN_OFFSET],
            self::I_RULE_BODY   => new \SplQueue(),
        ];
    }

    /**
     * @param Readable $grammar
     * @param array $rule
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidRuleException
     */
    private function parseRule(Readable $grammar, array $rule): void
    {
        if ($this->lastRule === null) {
            $error    = 'Before determining a non-terminal symbol "%s", it must be declared';
            $error    = \sprintf($error, $rule[Output::I_TOKEN_BODY]);
            $position = $grammar->getPosition($rule[Output::I_TOKEN_OFFSET]);

            throw InvalidRuleException::fromFile($error, $grammar, $position);
        }

        /** @var \SplStack $stack */
        $stack = $this->rules[$this->lastRule][self::I_RULE_BODY];
        $stack->push($rule);
    }

    /**
     * @return iterable
     * @throws \Railt\Compiler\Grammar\Exceptions\GrammarException
     */
    public function getData(): iterable
    {
        if ($this->lastRule === null) {
            throw new GrammarException('Grammar file must contain more than one rule');
        }

        $analyzer = new Analyzer($this->tokens->getData(), $this->rules);

        return $analyzer->getRules();
    }
}
