<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader;

use Railt\Compiler\Generator\Grammar\Exceptions\GrammarException;
use Railt\Compiler\Generator\Grammar\Exceptions\InvalidRuleException;
use Railt\Compiler\Generator\Grammar\Lexer;
use Railt\Compiler\Generator\Grammar\Reader\Productions\Context;
use Railt\Compiler\Generator\Grammar\Reader\Productions\InputRule;
use Railt\Compiler\Lexer\Tokens\Output;
use Railt\Io\Readable;

/**
 * Class ParsingState
 */
class ReadProductions implements State
{
    /**@#+
     * Rule body info
     */
    public const I_RULE_FILE   = 0x00;
    public const I_RULE_OFFSET = 0x01;
    public const I_RULE_BODY   = 0x02;
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
     * @var ReadTokens
     */
    private $tokens;

    /**
     * ParsingState constructor.
     * @param ReadTokens $tokens
     */
    public function __construct(ReadTokens $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @param Readable $grammar
     * @param array $rule
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    public function resolve(Readable $grammar, array $rule): void
    {
        if ($rule[Output::T_NAME] === Lexer::T_NODE_DEFINITION) {
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
        $this->lastRule = \trim($rule[Output::T_VALUE], " :\t\n\r\0\x0B");

        $this->rules[$this->lastRule] = [
            self::I_RULE_FILE   => $grammar,
            self::I_RULE_OFFSET => $rule[Output::T_OFFSET],
            self::I_RULE_BODY   => [],
        ];
    }

    /**
     * @param Readable $grammar
     * @param array $rule
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\InvalidRuleException
     */
    private function parseRule(Readable $grammar, array $rule): void
    {
        if ($this->lastRule === null) {
            $error    = 'Before determining a non-terminal symbol "%s", it must be declared';
            $error    = \sprintf($error, $rule[Output::T_VALUE]);
            $position = $grammar->getPosition($rule[Output::T_OFFSET]);

            throw InvalidRuleException::fromFile($error, $grammar, $position);
        }

        $this->rules[$this->lastRule][self::I_RULE_BODY][] = $rule;
    }

    /**
     * @return iterable
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    public function getData(): iterable
    {
        if ($this->lastRule === null) {
            throw new GrammarException('Grammar file must contain more than one rule');
        }

        foreach ($this->rules as $name => $data) {
            $ctx = new Context($data[self::I_RULE_FILE], $name, $data[self::I_RULE_OFFSET]);

            \array_walk($data[self::I_RULE_BODY], function (array $rule) use ($ctx): void {
                $ctx->collect(new InputRule($rule, $ctx));
            });

            yield from $ctx->reduce();
        }
    }
}
