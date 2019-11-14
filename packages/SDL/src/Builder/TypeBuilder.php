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
use Railt\SDL\Ast\Type\ListTypeNode;
use Railt\SDL\Ast\Type\NamedTypeNode;
use GraphQL\TypeSystem\Type\ListType;
use Railt\SDL\Ast\Type\NonNullTypeNode;
use Railt\SDL\Ast\Value\StringValueNode;
use GraphQL\TypeSystem\Type\NonNullType;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Ast\Generic\InterfaceImplementsCollection;
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
     * @param DefinitionInterface $definition
     * @return DefinitionInterface
     */
    protected function registered(DefinitionInterface $definition): DefinitionInterface
    {
        if ($definition instanceof DirectiveInterface) {
            return $this->registerDirective($definition);
        }

        if ($definition instanceof NamedTypeInterface) {
            return $this->registerType($definition);
        }

        throw new \InvalidArgumentException('Invalid definition ' . Facade::dump($definition));
    }

    /**
     * @param DirectiveInterface $directive
     * @return DirectiveInterface
     */
    protected function registerDirective(DirectiveInterface $directive): DirectiveInterface
    {
        $this->dictionary->directives->put($directive->getName(), $directive);

        return $directive;
    }

    /**
     * @param NamedTypeInterface $type
     * @return NamedTypeInterface
     */
    protected function registerType(NamedTypeInterface $type): NamedTypeInterface
    {
        $this->dictionary->typeMap->put($type->getName(), $type);

        return $type;
    }

    /**
     * @param TypeNode $type
     * @return TypeInterface
     */
    protected function buildType(TypeNode $type): TypeInterface
    {
        switch (true) {
            case $type instanceof NonNullTypeNode:
                return new NonNullType([
                    'ofType' => $this->buildType($type->type)
                ]);

            case $type instanceof ListTypeNode:
                return new ListType([
                    'ofType' => $this->buildType($type->type)
                ]);

            case $type instanceof NamedTypeNode:
                return $this->getType($type->name->value);

            default:
                throw new \LogicException('Unrecognized wrapping type node ' . \get_class($type));
        }
    }

    /**
     * @param string $type
     * @return TypeInterface
     */
    protected function getType(string $type): TypeInterface
    {
        return $this->builder->getType($type, $this->registry);
    }

    /**
     * @param DefinitionNode $node
     * @return DefinitionInterface
     */
    protected function buildDefinition(DefinitionNode $node): DefinitionInterface
    {
        return $this->builder->build($node, $this->registry);
    }

    /**
     * @param StringValueNode|null $string
     * @return string|null
     */
    protected function value(?StringValueNode $string): ?string
    {
        return $string ? $string->value : null;
    }

    /**
     * @param InterfaceImplementsCollection|NamedTypeNode[]|null $interfaces
     * @return \Traversable|TypeInterface[]
     */
    protected function buildImplementedInterfaces(?InterfaceImplementsCollection $interfaces): \Traversable
    {
        if ($interfaces === null) {
            return new \EmptyIterator();
        }

        foreach ($interfaces as $interface) {
            /** @var NamedTypeInterface $type */
            $type = $this->getType($interface->name->value);

            yield $type->getName() => $type;
        }
    }
}
