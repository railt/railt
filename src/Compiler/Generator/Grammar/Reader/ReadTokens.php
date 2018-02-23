<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader;

use Railt\Compiler\Generator\Grammar\Exceptions\InvalidTokenException;
use Railt\Compiler\Generator\Grammar\Lexer;
use Railt\Compiler\Lexer\Tokens\Output;
use Railt\Io\Readable;

/**
 * Class ReadTokens
 */
class ReadTokens implements State
{
    /**
     * @var array
     */
    private $tokens = [];

    /**
     * @param Readable $grammar
     * @param array $token
     */
    public function resolve(Readable $grammar, array $token): void
    {
        [$name, $value, $channel] = $this->tokenInfo($token);

        $this->checkTokenRedefinition($grammar, $token);

        $this->tokens[$name] = [$value, $channel];
    }

    /**
     * @param array $token
     * @return array
     */
    private function tokenInfo(array $token): array
    {
        return [
            $token[Output::T_CONTEXT][0],
            $token[Output::T_CONTEXT][1],
            $token[Output::T_CONTEXT][2] ?? Lexer::CHANNEL_TOKENS,
        ];
    }

    /**
     * @param Readable $grammar
     * @param array $token
     * @return void
     */
    private function checkTokenRedefinition(Readable $grammar, array $token): void
    {
        [$name, $value] = $token[Output::T_CONTEXT];

        if (\array_key_exists($name, $this->tokens)) {
            $offset   = $token[Output::T_OFFSET];
            $error    = 'Can not define token "%s" (%s) because token already defined.';
            $position = $grammar->getPosition($offset);

            throw InvalidTokenException::fromFile(\sprintf($error, $name, $value), $grammar, $position);
        }
    }

    /**
     * @return iterable
     */
    public function getData(): iterable
    {
        return $this->tokens;
    }
}
