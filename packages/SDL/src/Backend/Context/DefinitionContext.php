<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\ArgumentInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\EnumValueInterface;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use GraphQL\Contracts\TypeSystem\InputFieldInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Phplrt\Visitor\Traverser;
use Railt\SDL\Backend\Context;
use Railt\SDL\Backend\Runtime\DirectiveExecution;
use Railt\SDL\Backend\Runtime\DirectiveExecutionInterface;
use Railt\SDL\Backend\VariablesVisitor;
use Railt\SDL\Exception\RuntimeErrorException;
use Railt\SDL\Frontend\Ast\Definition\ArgumentDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\EnumValueDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\FieldDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\InputFieldDefinitionNode;
use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\ListTypeNode;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;
use Railt\SDL\Frontend\Ast\Type\NonNullTypeNode;
use Railt\SDL\Frontend\Ast\Type\TypeNode;
use Railt\SDL\Frontend\Ast\Value\VariableValueNode;
use Railt\TypeSystem\Argument;
use Railt\TypeSystem\EnumValue;
use Railt\TypeSystem\Field;
use Railt\TypeSystem\InputField;
use Railt\TypeSystem\Reference\TypeReference;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Railt\TypeSystem\Schema;
use Railt\TypeSystem\Type\ListType;
use Railt\TypeSystem\Type\NonNullType;
use Railt\TypeSystem\Type\WrappingType;
use Railt\TypeSystem\Value\StringValue;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class Record
 */
abstract class DefinitionContext implements DefinitionContextInterface
{
    /**
     * @var Schema
     */
    protected Schema $schema;

    /**
     * @var DefinitionNode
     */
    protected DefinitionNode $ast;

    /**
     * @var Context
     */
    private Context $context;

    /**
     * Record constructor.
     *
     * @param Context $context
     * @param Schema $schema
     * @param DefinitionNode $ast
     */
    public function __construct(Context $context, Schema $schema, DefinitionNode $ast)
    {
        $this->context = $context;
        $this->schema = $schema;
        $this->ast = $ast;
    }

    /**
     * @param iterable $ast
     * @param array $variables
     * @return iterable|Node
     */
    protected function precompile(iterable $ast, array $variables): iterable
    {
        $traverser = new Traverser([
            new VariablesVisitor($variables),
        ]);

        return $traverser->traverse($ast);
    }

    /**
     * @param EnumValueDefinitionNode $node
     * @return EnumValueInterface
     * @throws \Throwable
     */
    protected function buildEnumValueDefinition(EnumValueDefinitionNode $node): EnumValueInterface
    {
        $value = new EnumValue($node->name->value, [
            'description' => $this->descriptionOf($node),
        ]);

        foreach ($node->directives as $directive) {
            $this->executeDirective($value, $directive);
        }

        return $value;
    }

    /**
     * @param Node $node
     * @return string|null
     */
    protected function descriptionOf(Node $node): ?string
    {
        return $node->description instanceof StringValue
            ? $node->description->toPHPValue()
            : null;
    }

    /**
     * @param DefinitionInterface $ctx
     * @param DirectiveNode $node
     * @return void
     */
    protected function executeDirective(DefinitionInterface $ctx, DirectiveNode $node): void
    {
        $this->context->addExecution(
            $this->buildDirectiveExecution($ctx, $node)
        );
    }

    /**
     * @param DefinitionInterface $ctx
     * @param DirectiveNode $node
     * @return DirectiveExecutionInterface
     */
    protected function buildDirectiveExecution(
        DefinitionInterface $ctx,
        DirectiveNode $node
    ): DirectiveExecutionInterface {
        $arguments = [];

        foreach ($node->arguments as $argument) {
            $arguments[$argument->name->value] = $this->value($argument->value);
        }

        return new DirectiveExecution($node->name->name->value, $ctx, $arguments);
    }

    /**
     * @param ValueInterface $value
     * @return ValueInterface
     */
    protected function value(ValueInterface $value): ValueInterface
    {
        return $value;
    }

    /**
     * @param InputFieldDefinitionNode $node
     * @return InputFieldInterface
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    protected function buildInputFieldDefinition(InputFieldDefinitionNode $node): InputFieldInterface
    {
        $field = new InputField($node->name->value, $this->typeOf($node->type), [
            'description' => $this->descriptionOf($node),
        ]);

        if ($node->defaultValue) {
            $field->setDefaultValue($this->value($node->defaultValue));
        }

        foreach ($node->directives as $directive) {
            $this->executeDirective($field, $directive);
        }

        return $field;
    }

    /**
     * @param TypeNode $type
     * @return TypeReferenceInterface|WrappingType
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    protected function typeOf(TypeNode $type)
    {
        switch (true) {
            case $type instanceof NonNullTypeNode:
                return new NonNullType($this->typeOf($type->type));

            case $type instanceof ListTypeNode:
                return new ListType($this->typeOf($type->type));

            case $type instanceof NamedTypeNode:
                return $this->ref($type);

            default:
                throw new \InvalidArgumentException('Invalid type ref');
        }
    }

    /**
     * @param NamedTypeNode $node
     * @return TypeReferenceInterface
     */
    protected function ref(NamedTypeNode $node): TypeReferenceInterface
    {
        return new TypeReference($this->schema, $node->name->value);
    }

    /**
     * @param FieldDefinitionNode $node
     * @return FieldInterface
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    protected function buildFieldDefinition(FieldDefinitionNode $node): FieldInterface
    {
        $field = new Field($node->name->value, $this->typeOf($node->type), [
            'description' => $this->descriptionOf($node),
        ]);

        foreach ($node->arguments as $argument) {
            $field->addArgument($this->buildArgumentDefinition($argument));
        }

        foreach ($node->directives as $directive) {
            $this->executeDirective($field, $directive);
        }

        return $field;
    }

    /**
     * @param ArgumentDefinitionNode $node
     * @return ArgumentInterface
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    protected function buildArgumentDefinition(ArgumentDefinitionNode $node): ArgumentInterface
    {
        $argument = new Argument($node->name->value, $this->typeOf($node->type), [
            'description' => $this->descriptionOf($node),
        ]);

        if ($node->defaultValue) {
            $argument->setDefaultValue($this->value($node->defaultValue));
        }

        foreach ($node->directives as $directive) {
            $this->executeDirective($argument, $directive);
        }

        return $argument;
    }
}
