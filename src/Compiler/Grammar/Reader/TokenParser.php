<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\Grammar\Lexer\Grammar;
use Railt\Compiler\TokenInterface;
use Railt\Io\Readable;

/**
 * Class TokenRule
 */
class TokenParser implements Step
{
    /**
     * @var array|string[]
     */
    private $tokens = [];

    /**
     * @var array|string[]
     */
    private $skip = [];

    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function match(TokenInterface $token): bool
    {
        return \in_array($token->name(), [Grammar::T_TOKEN, Grammar::T_SKIP], true);
    }

    /**
     * @param Readable $file
     * @param TokenInterface $token
     */
    public function parse(Readable $file, TokenInterface $token): void
    {
        if ($token->name() === Grammar::T_SKIP) {
            $this->skip[] = $token->value(1);
        }

        $this->tokens[$token->value(1)] = $token->value(2);
    }

    /**
     * @return array|string[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @return array|string[]
     */
    public function getSkippedTokens(): array
    {
        return $this->skip;
    }
}
