<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\Grammar\Exceptions\UnprocessableProductionException;
use Railt\Compiler\Grammar\Lexer\Generator;
use Railt\Compiler\Grammar\Lexer\GrammarToken;
use Railt\Compiler\Grammar\Reader;
use Railt\Compiler\Lexer\Token;
use Railt\Io\Readable;

/**
 * Class Production
 */
class ProductionParser implements Step
{
    private const PRODUCTION_TOKENS = [
        GrammarToken::T_NODE_DEFINITION,
        GrammarToken::T_OR,
        GrammarToken::T_ZERO_OR_ONE,
        GrammarToken::T_ONE_OR_MORE,
        GrammarToken::T_ZERO_OR_MORE,
        GrammarToken::T_N_TO_M,
        GrammarToken::T_ZERO_TO_M,
        GrammarToken::T_N_OR_MORE,
        GrammarToken::T_EXACTLY_N,
        GrammarToken::T_SKIPPED,
        GrammarToken::T_KEPT,
        GrammarToken::T_NAMED,
        GrammarToken::T_NODE,
        GrammarToken::T_GROUP_OPEN,
        GrammarToken::T_GROUP_CLOSE,
    ];

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var array
     */
    private $rules = [];

    /**
     * @var string
     */
    private $currentRule;

    /**
     * TokenRule constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param Token $token
     * @return bool
     */
    public function match(Token $token): bool
    {
        return $token->is(...self::PRODUCTION_TOKENS) || $token->isEof();
    }

    /**
     * @param Readable $file
     * @param Token $token
     */
    public function parse(Readable $file, Token $token): void
    {
        if ($token->is(GrammarToken::T_NODE_DEFINITION)) {
            $this->createRule($token);
            return;
        }

        $this->pushRule($file, $token);
    }

    /**
     * @param Readable $file
     * @param Token $token
     */
    private function pushRule(Readable $file, Token $token): void
    {
        if ($this->currentRule === null) {
            $error = \vsprintf('The production body "%s" (%s) required its definition', [
                $token->value(),
                Generator::getName((int)$token->name()),
            ]);

            throw UnprocessableProductionException::fromFile($error, $file, $token->offset());
        }

        $this->rules[$this->currentRule][] = $token;
    }

    /**
     * @param Token $token
     */
    private function createRule(Token $token): void
    {
        $this->currentRule               = $token->get(0);
        $this->rules[$this->currentRule] = [];
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
