<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Railt\Compiler\Lexer\Definition;
use Railt\Compiler\Lexer\PCRECompiler;
use Railt\Compiler\Lexer\Result\Eof;
use Railt\Compiler\Lexer\Result\Token;
use Railt\Compiler\Lexer\Runtime;
use Railt\Io\Readable;

/**
 * Class Lexer
 * @mixin PCRECompiler
 */
class Lexer extends Runtime
{
    /**
     * @var PCRECompiler
     */
    private $compiler;

    /**
     * Lexer constructor.
     * @param iterable $definitions
     */
    public function __construct(iterable $definitions)
    {
        $this->compiler = new PCRECompiler($definitions);
        $this->eof(true);
        $this->compile();
    }

    /**
     * @param bool $keep
     * @return LexerInterface
     */
    public function eof(bool $keep = true): LexerInterface
    {
        $this->compiler->addToken(new Definition(Eof::NAME, "\0"));

        return parent::eof($keep);
    }

    /**
     * @return void
     */
    private function compile(): void
    {
        foreach ($this->compiler->getTokens() as $def) {
            $this->tokens[$def->getName()] = ! $def->isSkipped();
        }

        $this->pattern = $this->compiler->compile();
    }

    /**
     * @param Readable $input
     * @return \Traversable|Token[]
     */
    public function lex(Readable $input): \Traversable
    {
        $this->compile();

        foreach (parent::lex($input) as $token) {
            if ($this->tokens[$token->name()]) {
                yield $token;
            }
        }
    }

    /**
     * @return PCRECompiler
     */
    public function getCompiler(): PCRECompiler
    {
        return $this->compiler;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call(string $name, array $arguments = [])
    {
        if (\method_exists($this->compiler, $name)) {
            return $this->compiler->$name(...$arguments);
        }

        $error = 'Could not call a non-existent method %s::%s of ';
        throw new \BadMethodCallException(\sprintf($error, static::class, $name));
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'pattern' => $this->pattern,
            'tokens'  => $this->tokens,
        ];
    }
}
