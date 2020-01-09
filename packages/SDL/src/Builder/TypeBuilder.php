<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\SDL\Document;
use Railt\Dumper\Facade;
use Railt\SDL\Executor\Registry;
use Railt\SDL\Ast\Type\TypeNode;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Value\ValueNode;
use Railt\SDL\Ast\Type\ListTypeNode;
use Railt\SDL\Ast\Type\NamedTypeNode;
use Railt\TypeSystem\Type\ListType;
use Railt\SDL\Ast\Type\NonNullTypeNode;
use Railt\TypeSystem\Type\NonNullType;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Class Builder
 */
abstract class TypeBuilder
{
    /**
     * @var DefinitionNode
     */
    protected DefinitionNode $ast;

    /**
     * @var Document
     */
    protected Document $dictionary;

    /**
     * @var Factory
     */
    private Factory $builder;

    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * Builder constructor.
     *
     * @param Factory $builder
     * @param Registry $registry
     * @param Document $dictionary
     * @param DefinitionNode $node
     */
    public function __construct(Factory $builder, Registry $registry, Document $dictionary, DefinitionNode $node)
    {
        $this->builder = $builder;
        $this->ast = $node;
        $this->registry = $registry;
        $this->dictionary = $dictionary;
    }

    /**
     * @return DefinitionInterface
     */
    abstract public function build(): DefinitionInterface;

    /**
     * Registers a type in the system in order to be able to build correct
     * recursive relationship in the future.
     *
     * <code>
     *  input Example {     # Registration action
     *      field: Example  # Usage (recursive relation)
     *  }
     *
     *  $input = $this->register(
     *          new InputObject(...)
     *      )
     *      ->withFields(...build fields...)
     *  ;
     * </code>
     *
     * @param DefinitionInterface $definition
     * @return DefinitionInterface
     */
    protected function register(DefinitionInterface $definition): DefinitionInterface
    {
        if ($definition instanceof DirectiveInterface) {
            $this->dictionary->addDirective($definition);

            return $definition;
        }

        if ($definition instanceof NamedTypeInterface) {
            $this->dictionary->addType($definition);

            return $definition;
        }

        throw new \InvalidArgumentException('Invalid definition ' . Facade::dump($definition));
    }

    /**
     * Method for building a type hint.
     *
     * <code>
     *  type Example {
     *      field: [ExampleTypeHint]!
     *      #      ^^^^^^^^^^^^^^^^^^
     *  }
     * </code>
     *
     * @param TypeNode $type
     * @return TypeInterface
     */
    protected function hint(TypeNode $type): TypeInterface
    {
        switch (true) {
            case $type instanceof NonNullTypeNode:
                return new NonNullType([
                    'ofType' => $this->hint($type->type),
                ]);

            case $type instanceof ListTypeNode:
                return new ListType([
                    'ofType' => $this->hint($type->type),
                ]);

            case $type instanceof NamedTypeNode:
                return $this->fetch($type->name->value);

            default:
                throw new \LogicException('Unrecognized wrapping type node ' . \get_class($type));
        }
    }

    /**
     * Fetch type by definition's name.
     *
     * @param string $name
     * @return TypeInterface
     */
    protected function fetch(string $name): TypeInterface
    {
        return $this->builder->fetch($name, $this->registry);
    }

    /**
     * Method for building a dependencies, presented in the form of a
     * full-fledged AST.
     *
     * <code>
     *  $this->makeAll([<field's AST>, <field's AST>]); // [FieldInterface, FieldInterface]
     *  $this->makeAll([<argument's AST>]);             // [ArgumentInterface]
     * </code>
     *
     * @param iterable|DefinitionNode[] $definitions
     * @return iterable|DefinitionInterface[]
     */
    protected function makeAll(iterable $definitions): iterable
    {
        foreach ($definitions as $definition) {
            yield $this->make($definition);
        }
    }

    /**
     * Method for building a dependency, presented in the form of a
     * full-fledged AST.
     *
     * <code>
     *  $this->make(<type's AST>);      // ObjectTypeInterface
     *  $this->make(<field's AST>);     // FieldInterface
     *  $this->make(<argument's AST>);  // ArgumentInterface
     * </code>
     *
     * @param DefinitionNode $node
     * @return DefinitionInterface
     */
    protected function make(DefinitionNode $node): DefinitionInterface
    {
        return $this->builder->build($node, $this->registry);
    }

    /**
     * Returns a php representation of a value.
     *
     * <code>
     *  $this->value(<input's AST like {a: 42, b: 23}>);    // ['a' => 42, 'b' => 23]
     *  $this->value(<string's AST like "string">);         // 'string'
     * </code>
     *
     * @param ValueNode|null $value
     * @return mixed
     */
    protected function value(?ValueNode $value)
    {
        return $value ? $value->toNative() : null;
    }
}
