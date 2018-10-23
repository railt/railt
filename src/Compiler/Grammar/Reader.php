<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar;

use Railt\Compiler\Exception\GrammarException;
use Railt\Compiler\Exception\UnexpectedTokenException;
use Railt\Compiler\Grammar\Lexer\Grammar;
use Railt\Compiler\Grammar\Reader\PragmaParser;
use Railt\Compiler\Grammar\Reader\ProductionParser;
use Railt\Compiler\Grammar\Reader\Step;
use Railt\Compiler\Grammar\Reader\TokenParser;
use Railt\Compiler\LexerInterface;
use Railt\Compiler\TokenInterface;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class Reader
 */
class Reader
{
    /**
     * File extensions list
     */
    private const FILE_EXTENSIONS = [
        '',
        '.pp',
        '.pp2',
    ];

    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * @var array|Step[]
     */
    private $steps = [];

    /**
     * Reader constructor.
     */
    public function __construct()
    {
        $this->lexer = new Grammar();
        $this->bootReaderSteps();
    }

    /**
     * @return void
     */
    private function bootReaderSteps(): void
    {
        $this->steps = [
            TokenParser::class      => new TokenParser(),
            PragmaParser::class     => new PragmaParser(),
            ProductionParser::class => new ProductionParser(),
        ];
    }

    /**
     * @return LexerInterface
     */
    public function getLexer(): LexerInterface
    {
        return $this->lexer;
    }

    /**
     * @param Readable $input
     * @return ParsingResult
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function read(Readable $input): ParsingResult
    {
        /** @var Readable $file */
        foreach ($this->lex($input) as $file => $token) {
            $this->process($file, $token);
        }

        return new ParsingResult(
            $this->steps[PragmaParser::class],
            $this->steps[TokenParser::class],
            $this->steps[ProductionParser::class]
        );
    }

    /**
     * @param Readable $input
     * @return \Traversable
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function lex(Readable $input): \Traversable
    {
        $tokens = $this->lexer->lex($input);

        foreach ($tokens as $token) {
            if ($token->name() === TokenInterface::END_OF_INPUT) {
                continue;
            }

            if ($token->name() === Grammar::T_INCLUDE) {
                yield from $this->lex($this->include($input, $token));
                continue;
            }

            yield $input => $token;
        }
    }

    /**
     * @param Readable $from
     * @param TokenInterface $token
     * @return Readable
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function include(Readable $from, TokenInterface $token): Readable
    {
        $path = $token->value(1);

        foreach (self::FILE_EXTENSIONS as $extension) {
            $file = \dirname($from->getPathname()) . '/' . $path . $extension;

            if (\is_file($file)) {
                return File::fromPathname($file);
            }
        }

        $error = \sprintf('Could not read external grammar file "%s"', $path);
        throw GrammarException::fromFile($error, $from, $token->offset());
    }

    /**
     * @param Readable $file
     * @param TokenInterface $token
     * @return bool
     */
    private function process(Readable $file, TokenInterface $token): bool
    {
        foreach ($this->steps as $step) {
            if ($step->match($token)) {
                $step->parse($file, $token);

                return true;
            }
        }

        $error = \sprintf('Grammar semantic error, invalid token %s', $token);
        throw UnexpectedTokenException::fromFile($error, $file, $token->offset());
    }
}
