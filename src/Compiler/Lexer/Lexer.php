<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

/**
 * Class Lexer
 * @mixin Regex
 */
class Lexer extends Runtime
{
    /**
     * @var Regex
     */
    private $regex;

    /**
     * DynamicRuntime constructor.
     * @param array|Definition[]|\Traversable $tokens
     */
    public function __construct(iterable $tokens = [])
    {
        $this->regex = new Regex($tokens);
    }

    /**
     * @return string
     */
    public function pattern(): string
    {
        return $this->regex->toString();
    }

    /**
     * @return array
     */
    public function channels(): array
    {
        $result = [Token::T_EOF => Definition::CHANNEL_SYSTEM];

        foreach ($this->regex->getTokens() as $token) {
            if ($token->getChannel() === Definition::CHANNEL_DEFAULT) {
                continue;
            }

            $result[$token->getId()] = $token->getChannel();
        }

        return $result;
    }

    /**
     * @return array
     */
    public function identifiers(): array
    {
        $result = [Token::T_EOF => Token::T_EOF_NAME];

        foreach ($this->regex->getTokens() as $token) {
            $result[$token->getId()] = $token->getName();
        }

        return $result;
    }

    /**
     * @param Definition[] $tokens
     * @return Lexer
     */
    public function addToken(Definition ...$tokens): self
    {
        foreach ($tokens as $token) {
            $this->regex->addToken($token);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call(string $name, array $arguments = [])
    {
        if (\method_exists($this->regex, $name)) {
            return $this->regex->$name(...$arguments);
        }

        $error = 'Could not call a non-existent method %s::%s of ';
        throw new \BadMethodCallException(\sprintf($error, static::class, $name));
    }
}
