<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\UnionType;
use Serafim\Railgun\Adapters\Webonyx\BuilderInterface;
use Serafim\Railgun\Adapters\Webonyx\Support\IterablesBuilder;
use Serafim\Railgun\Adapters\Webonyx\Support\NameBuilder;
use Serafim\Railgun\Contracts\SchemaInterface;
use Serafim\Railgun\Contracts\TypeDefinitionInterface;
use Serafim\Railgun\Contracts\Types\EnumTypeInterface;
use Serafim\Railgun\Contracts\Types\InterfaceTypeInterface;
use Serafim\Railgun\Contracts\Types\ObjectTypeInterface;
use Serafim\Railgun\Contracts\Types\TypeInterface;
use Serafim\Railgun\Contracts\Types\UnionTypeInterface;
use Serafim\Railgun\Types\Schemas\Fields;

/**
 * Class TypesBuilder
 * @package Serafim\Railgun\Adapters\Webonyx\Builders
 */
class TypesBuilder
{
    use NameBuilder;
    use IterablesBuilder;

    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * TypesBuilder constructor.
     * @param BuilderInterface $builder
     */
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param TypeInterface $type
     * @return Type|array
     * @throws \InvalidArgumentException
     */
    public function build(TypeInterface $type): Type
    {
        switch (true) {
            case $type instanceof ObjectTypeInterface:
                return $this->makeObjectType($type);

            case $type instanceof EnumTypeInterface:
                return $this->makeEnumType($type);

            case $type instanceof UnionTypeInterface:
                return $this->makeUnionType($type);

            case $type instanceof InterfaceTypeInterface:
                return $this->makeInterfaceType($type);
        }

        throw new \InvalidArgumentException('Invalid type definition for: ' . get_class($type));
    }

    /**
     * @param ObjectTypeInterface $object
     * @return ObjectType
     * @throws \InvalidArgumentException
     */
    private function makeObjectType(ObjectTypeInterface $object): ObjectType
    {
        return new ObjectType(array_merge($this->makeName($object), [
            // Fields
            'fields'     => $this->builder->getPartialsBuilder()
                ->makeIterable($object->getFields($this->getFieldsSchema())),

            // Interfaces
            'interfaces' => $this->makeIterableValues($object->getInterfaces(), 'type'),

            // TODO 'isTypeOf' => function($value, $context, ResolveInfo $info) { ... }
        ]));
    }

    /**
     * @return Fields|SchemaInterface
     */
    private function getFieldsSchema(): Fields
    {
        return $this->builder->getRegistry()->schema(Fields::class);
    }

    /**
     * @param EnumTypeInterface $enum
     * @return EnumType
     */
    private function makeEnumType(EnumTypeInterface $enum): EnumType
    {
        return new EnumType(array_merge($this->makeName($enum), [
            'values' => $this->builder->getPartialsBuilder()->makeIterable($enum->getValues()),
        ]));
    }

    /**
     * @param UnionTypeInterface $union
     * @return UnionType
     */
    private function makeUnionType(UnionTypeInterface $union): UnionType
    {
        return new UnionType([
            'types' => $this->makeIterableValues($union->getTypes(), 'type'),
            // TODO 'resolveType' => function($value, $context, ResolveInfo $info): ObjectType { ... }
        ]);
    }

    /**
     * @param InterfaceTypeInterface $interface
     * @return InterfaceType
     */
    private function makeInterfaceType(InterfaceTypeInterface $interface): InterfaceType
    {
        return new InterfaceType(array_merge($this->makeName($interface), [
            // Fields
            'fields' => $this->builder->getPartialsBuilder()
                ->makeIterable($interface->getFields($this->getFieldsSchema())),

            // TODO 'resolveType' => function($value, $context, ResolveInfo $info): ObjectType { ... }
        ]));
    }

    /**
     * @param TypeDefinitionInterface $definition
     * @return Type
     * @throws \InvalidArgumentException
     */
    public function buildTypeDefinition(TypeDefinitionInterface $definition): Type
    {
        $type = $this->builder->type($definition->getTypeName());

        if ($definition->isList()) {
            $type = Type::listOf($type);
        }

        return $definition->isNullable() ? $type : Type::nonNull($type);
    }
}
