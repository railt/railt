<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Execution;

use Railt\SDL\Document;
use Phplrt\Visitor\Visitor;
use Phplrt\Visitor\Traverser;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Name\IdentifierNode;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Ast\Executable\ArgumentNode;
use Railt\SDL\Ast\Executable\DirectiveNode;
use Railt\SDL\Runtime\Type\DirectiveExecution;
use Railt\SDL\Ast\Generic\DirectiveCollection;
use Railt\SDL\Ast\Extension\TypeExtensionNode;
use Railt\SDL\Ast\Definition\TypeDefinitionNode;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use Railt\SDL\Ast\Extension\SchemaExtensionNode;
use Railt\SDL\Ast\Definition\FieldDefinitionNode;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Railt\SDL\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Ast\Definition\EnumTypeDefinitionNode;
use Railt\SDL\Ast\Definition\ArgumentDefinitionNode;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Ast\Definition\EnumValueDefinitionNode;
use Railt\SDL\Ast\Definition\ObjectTypeDefinitionNode;
use Railt\SDL\Ast\Definition\InputFieldDefinitionNode;
use GraphQL\Contracts\TypeSystem\Type\EnumTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\SDL\Ast\Definition\InterfaceTypeDefinitionNode;
use Railt\SDL\Ast\Definition\InputObjectTypeDefinitionNode;
use GraphQL\Contracts\TypeSystem\Common\FieldsAwareInterface;
use GraphQL\Contracts\TypeSystem\Type\InputObjectTypeInterface;

/**
 * Class DirectiveExecutor
 */
class DirectiveExecutor extends Visitor
{
    /**
     * @var Document
     */
    private Document $document;

    /**
     * DirectiveExecutor constructor.
     *
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * @param NodeInterface $node
     * @return int|null
     */
    public function enter(NodeInterface $node): ?int
    {
        switch (true) {
            case $node instanceof TypeDefinitionNode:
            case $node instanceof TypeExtensionNode:
                $this->applyDefinition($this->getType($node->name), $node);
                break;

            case $node instanceof SchemaDefinitionNode:
            case $node instanceof SchemaExtensionNode:
                $this->apply($this->getSchema(), $node->directives);
                break;
        }

        return Traverser::DONT_TRAVERSE_CHILDREN;
    }

    /**
     * @param NamedTypeInterface $context
     * @param TypeDefinitionNode|TypeExtensionNode|DefinitionNode $node
     * @return void
     */
    private function applyDefinition(NamedTypeInterface $context, DefinitionNode $node): void
    {
        $this->apply($context, $node->directives);

        switch (true) {
            case $node instanceof ObjectTypeDefinitionNode:
            case $node instanceof InterfaceTypeDefinitionNode:
                \assert($context instanceof FieldsAwareInterface);

                $this->applyFields($context, $node);
                break;

            case $node instanceof InputObjectTypeDefinitionNode:
                \assert($context instanceof InputObjectTypeInterface);

                $this->applyInputFields($context, $node);
                break;

            case $node instanceof EnumTypeDefinitionNode:
                \assert($context instanceof EnumTypeInterface);

                $this->applyEnumValues($context, $node);
                break;
        }
    }

    /**
     * @param DefinitionInterface $context
     * @param DirectiveCollection|null $collection
     * @return iterable|DirectiveExecution[]
     */
    protected function apply(DefinitionInterface $context, ?DirectiveCollection $collection): iterable
    {
        $result = [];

        if ($collection && $collection->count()) {
            foreach ($collection as $node) {
                $result[] = $this->add($context, $node);
            }
        }

        return $result;
    }

    /**
     * @param DefinitionInterface $context
     * @param DirectiveNode $directive
     * @return DirectiveExecution
     */
    private function add(DefinitionInterface $context, DirectiveNode $directive): DirectiveExecution
    {
        $definition = $this->document->getDirective($directive->name->value);

        $execution = new DirectiveExecution($definition, $context, $this->fetchArguments($directive));

        $this->document->addExecution($execution);

        return $execution;
    }

    /**
     * @param DirectiveNode $node
     * @return array
     */
    private function fetchArguments(DirectiveNode $node): array
    {
        $result = [];

        /** @var ArgumentNode $argument */
        foreach ($node->arguments ?? [] as $argument) {
            $result[$argument->name->value] = $argument->value->toNative();
        }

        return $result;
    }

    /**
     * @param FieldsAwareInterface $context
     * @param TypeDefinitionNode $node
     * @return void
     */
    private function applyFields(FieldsAwareInterface $context, TypeDefinitionNode $node): void
    {
        /** @var FieldDefinitionNode $field */
        foreach ($node->fields ?? [] as $field) {
            /** @var FieldInterface $definition */
            $this->apply($definition = $context->getField($field->name->value), $field->directives);

            /** @var ArgumentDefinitionNode $argument */
            foreach ($field->arguments ?? [] as $argument) {
                $this->apply($definition->getArgument($argument->name->value), $argument->directives);
            }
        }
    }

    /**
     * @param InputObjectTypeInterface $context
     * @param InputObjectTypeDefinitionNode $node
     * @return void
     */
    private function applyInputFields(InputObjectTypeInterface $context, InputObjectTypeDefinitionNode $node): void
    {
        /** @var InputFieldDefinitionNode $field */
        foreach ($node->fields ?? [] as $field) {
            $this->apply($context->getField($field->name->value), $field->directives);
        }
    }

    /**
     * @param EnumTypeInterface $context
     * @param EnumTypeDefinitionNode $node
     * @return void
     */
    private function applyEnumValues(EnumTypeInterface $context, EnumTypeDefinitionNode $node): void
    {
        /** @var EnumValueDefinitionNode $value */
        foreach ($node->values ?? [] as $value) {
            $this->apply($context->getValue($value->name->value), $value->directives);
        }
    }

    /**
     * @param IdentifierNode $id
     * @return NamedTypeInterface
     */
    protected function getType(IdentifierNode $id): NamedTypeInterface
    {
        $context = $this->document->getType($id->value);

        \assert($context instanceof NamedTypeInterface);

        return $context;
    }

    /**
     * @return SchemaInterface
     */
    protected function getSchema(): SchemaInterface
    {
        $context = $this->document->getSchema();

        \assert($context instanceof SchemaInterface);

        return $context;
    }
}
