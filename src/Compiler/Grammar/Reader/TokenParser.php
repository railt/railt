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
use Railt\Compiler\Grammar\Reader;
use Railt\Compiler\Lexer\Definition;
use Railt\Compiler\Lexer\TokenInterface;
use Railt\Io\Readable;

/**
 * Class TokenRule
 */
class TokenParser implements Step
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var array|Definition[]
     */
    private $tokens = [];

    /**
     * TokenRule constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function match(TokenInterface $token): bool
    {
        return $token->is(Grammar::T_TOKEN, Grammar::T_SKIP);
    }

    /**
     * @param Readable $file
     * @param TokenInterface $token
     */
    public function parse(Readable $file, TokenInterface $token): void
    {
        $definition = new Definition($token->value(0), $token->value(1));

        if ($token->is(Grammar::T_SKIP)) {
            $definition->skip();
        }

        $this->tokens[] = $definition;
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }
}
