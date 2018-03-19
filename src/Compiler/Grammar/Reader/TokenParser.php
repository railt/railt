<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\Lexer\Token;
use Railt\Compiler\Lexer\Definition;
use Railt\Compiler\Grammar\Lexer\GrammarToken;
use Railt\Compiler\Grammar\Reader;
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
     * @param Token $token
     * @return bool
     */
    public function match(Token $token): bool
    {
        return $token->is(GrammarToken::T_TOKEN, GrammarToken::T_SKIP);
    }

    /**
     * @param Readable $file
     * @param Token $token
     */
    public function parse(Readable $file, Token $token): void
    {
        $definition = new Definition($token->get(0), $token->get(1));

        if ($token->is(GrammarToken::T_SKIP)) {
            $definition->in(Token::CHANNEL_SKIP);
        } else {
            $definition->in($token->get(2) ?? Token::CHANNEL_DEFAULT);
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
