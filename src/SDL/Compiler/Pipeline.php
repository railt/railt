<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Exception\UnrecognizedTokenException;
use Railt\Compiler\Parser;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Exceptions\CompilerException;
use Railt\SDL\Compiler\Parser\Factory;
use Railt\SDL\Compiler\SymbolTable\Builder;

/**
 * Class Pipeline
 */
class Pipeline
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Builder
     */
    private $table;

    /**
     * Pipeline constructor.
     * @throws \LogicException
     */
    public function __construct()
    {
        $this->parser = (new Factory())->getParser();
        $this->table  = new Builder();
    }

    /**
     * @param Readable $input
     * @return NodeInterface
     * @throws \Railt\Compiler\Exception\UnexpectedTokenException
     * @throws \Railt\Compiler\Exception\UnrecognizedTokenException
     * @throws \Railt\SDL\Compiler\Exceptions\CompilerException
     */
    private function parse(Readable $input): NodeInterface
    {
        try {
            return $this->parser->parse($input->getContents());
        } catch (UnrecognizedTokenException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new CompilerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param Readable $input
     * @param NodeInterface $ast
     * @return SymbolTable
     */
    private function build(Readable $input, NodeInterface $ast): SymbolTable
    {
        return $this->table->build($input, $ast);
    }

    /**
     * @param Readable $input
     * @return void
     */
    public function process(Readable $input)
    {
        /**
         * Build an AST
         */
        $ast = $this->parse($input);

        /**
         * Build symbol table
         */
        $table = $this->build($input, $ast);

        dd($table);
    }
}
