<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\Exception\UnprocessableProductionException;
use Railt\Compiler\Grammar\Lexer\Grammar;
use Railt\Compiler\TokenInterface;
use Railt\Io\Readable;

/**
 * Class Production
 */
class ProductionParser implements Step
{
    private const PRODUCTION_TOKENS = [
        Grammar::T_NODE_DEFINITION,
        Grammar::T_OR,
        Grammar::T_ZERO_OR_ONE,
        Grammar::T_ONE_OR_MORE,
        Grammar::T_ZERO_OR_MORE,
        Grammar::T_N_TO_M,
        Grammar::T_ZERO_TO_M,
        Grammar::T_N_OR_MORE,
        Grammar::T_EXACTLY_N,
        Grammar::T_SKIPPED,
        Grammar::T_KEPT,
        Grammar::T_NAMED,
        Grammar::T_NODE,
        Grammar::T_GROUP_OPEN,
        Grammar::T_GROUP_CLOSE,
    ];

    /**
     * @var array
     */
    private $rules = [];

    /**
     * @var string
     */
    private $currentRule;

    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function match(TokenInterface $token): bool
    {
        return \in_array($token->name(), self::PRODUCTION_TOKENS, true);
    }

    /**
     * @param Readable $file
     * @param TokenInterface $token
     */
    public function parse(Readable $file, TokenInterface $token): void
    {
        if ($token->name() === Grammar::T_NODE_DEFINITION) {
            $this->createRule($token);

            return;
        }

        $this->pushRule($file, $token);
    }

    /**
     * @param TokenInterface $token
     */
    private function createRule(TokenInterface $token): void
    {
        $this->currentRule = $token->value(1);

        $this->rules[$this->currentRule] = [];
    }

    /**
     * @param Readable $file
     * @param TokenInterface $token
     */
    private function pushRule(Readable $file, TokenInterface $token): void
    {
        if ($this->currentRule === null) {
            $error = \sprintf('Syntax error, unprocessable token %s', $token);
            throw UnprocessableProductionException::fromFile($error, $file, $token->offset());
        }

        $this->rules[$this->currentRule][] = $token;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
