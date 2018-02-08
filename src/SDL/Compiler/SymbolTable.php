<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Compiler\Ast\RuleInterface;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Exceptions\LinkerException;
use Railt\SDL\Compiler\Exceptions\TypeConflictException;
use Railt\SDL\Compiler\Runtime\CallStackInterface;
use Railt\SDL\Compiler\SymbolTable\Context;
use Railt\SDL\Compiler\SymbolTable\Extractors\Extractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\ImportExtractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\InterfaceExtractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\NamespaceExtractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\ObjectExtractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\SchemaExtractor;
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * The Symbol Table contains a list of all type
 * names and their ASTs for their subsequent construction
 * in the form of an object model.
 */
class SymbolTable
{
    /**
     * @var array|Extractor[]
     */
    private $extractors;

    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * @var array
     */
    private $types = [];

    /**
     * Builder constructor.
     */
    public function __construct(CallStackInterface $stack)
    {
        $this->stack = $stack;

        $this->extractors = [
            '#NamespaceDefinition' => new NamespaceExtractor(),
            '#ImportDefinition'    => new ImportExtractor(),

            // TypeDefinitions
            '#SchemaDefinition'    => new SchemaExtractor(),
            '#ObjectDefinition'    => new ObjectExtractor(),
            '#InterfaceDefinition' => new InterfaceExtractor(),
        ];
    }

    /**
     * @param string $extractor
     */
    public function addExtractor(string $node, Extractor $extractor): void
    {
        $this->extractors[$node] = $extractor;
    }

    /**
     * @param Readable $input
     * @param RuleInterface $ast
     * @throws \LogicException
     */
    public function build(Readable $input, RuleInterface $ast)
    {
        $this->buildDocument($input, $ast);

        return $this;
    }

    /**
     * @param Readable $input
     * @param RuleInterface $ast
     * @param array $links
     * @return Context
     * @throws \LogicException
     * @throws \Railt\SDL\Compiler\Exceptions\TypeConflictException
     */
    private function buildDocument(Readable $input, RuleInterface $ast, array $links = []): Context
    {
        $context = $this->buildContext($input, $ast);

        foreach ($context->getRecords() as $record) {
            $this->stack->push($record);

            $fqn = $record->getFullyQualifiedName();

            if (\array_key_exists($fqn, $this->types)) {
                $previous = $this->types[$fqn];

                $error = 'Can not define "%s" %s because type already defined as %s';
                $error = \sprintf($error, $fqn, $record->getType(), $previous->getType());
                throw new TypeConflictException($error, $this->stack);
            }

            $this->types[$fqn] = $record;

            $this->stack->pop();
        }

        return $context;
    }

    /**
     * @param Readable $input
     * @param RuleInterface $ast
     * @return Context
     * @throws \LogicException
     */
    private function buildContext(Readable $input, RuleInterface $ast): Context
    {
        $context = new Context($input);

        foreach ($ast->getChildren() as $child) {
            $extractor = $this->extractors[$child->getName()] ?? null;

            if ($extractor !== null) {
                $extractor->extract($context, $child);
                continue;
            }

            $error = 'Internal Error: The Node "%s" does not match the allowable for unpacking';
            throw new \LogicException(\sprintf($error, $child->getName()), $this->stack);
        }

        return $context;
    }
}
