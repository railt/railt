<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Compiler\Ast\LeafInterface;
use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\Compiler\Exception\LexerException;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Exceptions\CompilerException;
use Railt\SDL\Compiler\Parser\Factory;
use Railt\SDL\Compiler\Pipeline;
use Railt\SDL\Compiler\SymbolTable;
use Railt\SDL\Compiler\SymbolTable\Builder;
use Railt\SDL\Exceptions\UnexpectedTokenException;

/**
 * Class Compiler
 */
class Compiler
{
    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * @param Readable $readable
     * @return SymbolTable
     */
    private function prepare(Readable $readable): SymbolTable
    {
        /**
         * The very first stage is the conversion from
         * source code to an abstract syntax tree (AST).
         *
         * At this point, errors may occur in an
         * unexpected (parser) or unrecognized (lexer) token.
         *
         * @see \Railt\Compiler\Ast\NodeInterface
         * @see \Railt\Compiler\Ast\RuleInterface
         * @see \Railt\Compiler\Ast\LeafInterface
         *
         * @var NodeInterface|RuleInterface $ast
         */
        $ast = (new Factory())->parse($readable);

        /**
         * The second stage of preparation is the
         * construction of a "symbol table". This
         * is a registry of names and types in the
         * source.
         *
         * @see https://en.wikipedia.org/wiki/Symbol_table
         *
         * @var SymbolTable $table
         */
        return (new Builder($ast, $readable))->build();
    }

    /**
     * @param Readable $readable
     * @return mixed|null
     * @throws \Railt\Compiler\Exception\LexerException
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws \Railt\SDL\Compiler\Exceptions\CompilerException
     */
    public function compile(Readable $readable)
    {
        $table = $this->prepare($readable);

        try {
            return $this->getPipeline()->resolve($readable);
        } catch (LexerException | UnexpectedTokenException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new CompilerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return Pipeline
     * @throws \LogicException
     */
    public function getPipeline(): Pipeline
    {
        if ($this->pipeline === null) {
            $this->pipeline = new DocumentBuilder();
        }

        return $this->pipeline;
    }
}
