<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Exceptions\CompilerException;
use Railt\SDL\Compiler\Parser\Factory;
use Railt\SDL\Compiler\SymbolTable;
use Railt\SDL\Compiler\SymbolTable\Extractors\ExtensionDefinitionExtractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\Extractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\SchemaDefinitionExtractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\TypeDefinitionExtractor;
use Railt\SDL\Runtime\CallStack;
use Railt\SDL\Runtime\CallStackInterface;

/**
 * Class Builder
 */
class Builder
{
    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * @var Factory
     */
    private $parsers;

    /**
     * @var array|Extractor[]
     */
    private $extractors = [];

    /**
     * @var SymbolTable
     */
    private $table;

    /**
     * Builder constructor.
     * @param CallStackInterface|null $stack
     * @param SymbolTable|null $table
     */
    public function __construct(CallStackInterface $stack = null, SymbolTable $table = null)
    {
        $this->stack = $stack ?? new CallStack();
        $this->table = $table ?? new SymbolTable();

        $this->parsers = new Factory();

        $this->addDefinitionExtractor(new TypeDefinitionExtractor());
        $this->addDefinitionExtractor(new SchemaDefinitionExtractor());
        $this->addDefinitionExtractor(new ExtensionDefinitionExtractor());
    }

    /**
     * @param Extractor $extractor
     * @return $this
     */
    public function addDefinitionExtractor(Extractor $extractor): self
    {
        $this->extractors[] = $extractor;

        return $this;
    }

    /**
     * @param Readable $input
     * @return SymbolTable
     * @throws \Railt\SDL\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exception\UnexpectedTokenException
     * @throws \Railt\Compiler\Exception\LexerException
     * @throws \Railt\Compiler\Exception\InvalidPragmaException
     * @throws \Railt\Compiler\Exception\Exception
     * @throws \LogicException
     */
    public function build(Readable $input): SymbolTable
    {
        /**
         * The very first stage is the conversion
         * from sourcecode to an abstract syntax
         * tree (AST).
         *
         * At this point, errors may occur in an
         * unexpected (parser) or unrecognized
         * (lexer) token.
         *
         * @see \Railt\Compiler\Ast\NodeInterface
         * @see \Railt\Compiler\Ast\RuleInterface
         * @see \Railt\Compiler\Ast\LeafInterface
         *
         * @var NodeInterface|RuleInterface $ast
         */
        $ast = $this->parsers->parse($input);

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
        return $this->fillTable($input, $this->table, $ast);
    }

    /**
     * @param Readable $input
     * @param SymbolTable $table
     * @param NodeInterface $ast
     * @return SymbolTable
     * @throws \Railt\SDL\Compiler\Exceptions\CompilerException
     */
    private function fillTable(Readable $input, SymbolTable $table, NodeInterface $ast): SymbolTable
    {
        \assert($ast instanceof RuleInterface);

        foreach ($ast->getChildren() as $child) {
            $this->extract($input, $table, $child);
        }

        return $table;
    }

    /**
     * @param Readable $input
     * @param SymbolTable $table
     * @param NodeInterface $ast
     * @return SymbolTable
     * @throws \Railt\SDL\Compiler\Exceptions\CompilerException
     */
    private function extract(Readable $input, SymbolTable $table, NodeInterface $ast): SymbolTable
    {
        \assert($ast instanceof RuleInterface);

        foreach ($this->extractors as $extractor) {
            if ($extractor->match($ast)) {
                $record = $extractor->extract($input, $ast);
                $record->setAst($ast);

                return $table->register($record);
            }
        }

        $error = 'Could not extract type and name from %s node';
        throw new CompilerException(\sprintf($error, $ast->getName()));
    }
}
