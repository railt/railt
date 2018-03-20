<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Railt\Compiler\Lexer\Result\Eof;
use Railt\Compiler\Lexer\Result\Token;
use Railt\Compiler\LexerInterface;
use Railt\Io\Readable;

/**
 * Class Runtime
 */
abstract class Runtime implements LexerInterface
{
    /**
     * @var array
     */
    protected $tokens = [];

    /**
     * @var string
     */
    protected $pattern;

    /**
     * @param Readable $input
     * @return \Traversable|Token[]|TokenStream
     */
    public function lex(Readable $input): \Traversable
    {
        return new TokenStream($input, $this->pattern, $this->tokens);
    }

    /**
     * @param bool $keep
     * @return Runtime|$this
     */
    public function eof(bool $keep = true): LexerInterface
    {
        $this->tokens[Eof::NAME] = $keep;

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return \array_key_exists($name, $this->tokens);
    }
}
