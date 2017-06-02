<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\{
    Type,
    EnumType,
    UnionType,
    ObjectType,
    InterfaceType
};
use Serafim\Railgun\{
    Endpoint,
    Schema\Fields,
    Schema\SchemaInterface
};
use Serafim\Railgun\Adapters\Webonyx\HashMap;
use Serafim\Railgun\Types\{
    Registry,
    InternalType,
    TypeInterface,
    EnumTypeInterface,
    UnionTypeInterface,
    ObjectTypeInterface,
    InterfaceTypeInterface
};

/**
 * Class TypeBuilder
 * @package Serafim\Railgun\Adapters\Webonyx\Builders
 */
class TypeBuilder
{
    use BuilderHelpers;

    /**
     * @var Endpoint
     */
    private $endpoint;

    /**
     * @var HashMap|Type[]
     */
    private $storage;

    /**
     * @var CreatorsBuilder
     */
    private $creators;

    /**
     * TypeBuilder constructor.
     * @param Endpoint $endpoint
     */
    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->storage = new HashMap();
        $this->creators = new CreatorsBuilder($this);
    }

    /**
     * @param string $name
     * @return TypeInterface
     * @throws \InvalidArgumentException
     */
    public function type(string $name): TypeInterface
    {
        return $this->endpoint->getTypes()->get($name);
    }

    /**
     * @param TypeInterface $type
     * @return Type
     * @throws \InvalidArgumentException
     */
    public function build(TypeInterface $type): Type
    {
        if (!$this->storage->has($type)) {
            $this->storage->set($type, $this->create($type));
        }

        return $this->storage->get($type);
    }

    /**
     * @param TypeInterface $type
     * @return Type
     * @throws \InvalidArgumentException
     */
    private function create(TypeInterface $type): Type
    {
        switch (true) {
            case $type instanceof InternalType:
                return $this->resolveInternalType($type);

            case $type instanceof InterfaceTypeInterface:
                return $this->buildInterfaceType($type);

            case $type instanceof EnumTypeInterface:
                return $this->buildEnumType($type);

            case $type instanceof UnionTypeInterface:
                return $this->buildUnionType($type);

            case $type instanceof ObjectTypeInterface:
                return $this->buildObjectType($type);
        }

        throw new \InvalidArgumentException('Invalid type ' . get_class($type));
    }

    /**
     * @param InternalType $type
     * @return Type
     * @throws \InvalidArgumentException
     */
    private function resolveInternalType(InternalType $type): Type
    {
        switch ($type->getName()) {
            case Registry::INTERNAL_TYPE_BOOLEAN:
                return Type::boolean();

            case Registry::INTERNAL_TYPE_FLOAT:
                return Type::float();

            case Registry::INTERNAL_TYPE_ID:
                return Type::id();

            case Registry::INTERNAL_TYPE_INT:
                return Type::int();

            case Registry::INTERNAL_TYPE_STRING:
                return Type::string();
        }

        throw new \InvalidArgumentException('Invalid scalar type ' . $type->getName());
    }

    /**
     * @param InterfaceTypeInterface $type
     * @return InterfaceType
     */
    public function buildInterfaceType(InterfaceTypeInterface $type): InterfaceType
    {
        return new InterfaceType(static::withInfo($type, [
            // TODO
            'fields' => [],

            // TODO 'resolveType' => function ($obj, $context, ResolveInfo $info) { ... }
        ]));
    }

    /**
     * @param EnumTypeInterface $type
     * @return EnumType
     */
    public function buildEnumType(EnumTypeInterface $type): EnumType
    {
        return new EnumType(static::withInfo($type, [
            // TODO
            'values' => [],
        ]));
    }

    /**
     * @param UnionTypeInterface $type
     * @return UnionType
     */
    public function buildUnionType(UnionTypeInterface $type): UnionType
    {
        return new UnionType(static::withInfo($type, [
            // TODO
            'types' => [],

            // TODO 'resolveType' => function ($obj, $context, ResolveInfo $info) { ... }
        ]));
    }

    /**
     * @param ObjectTypeInterface $type
     * @return ObjectType
     * @throws \InvalidArgumentException
     */
    public function buildObjectType(ObjectTypeInterface $type): ObjectType
    {
        $fields = $type->getFields($this->schema(Fields::class));

        return new ObjectType(static::withInfo($type, [
            'fields'     => $this->creators->buildFields($fields),

            // TODO
            'interfaces' => [],
        ]));
    }

    /**
     * @param string $name
     * @return SchemaInterface
     * @throws \InvalidArgumentException
     */
    public function schema(string $name): SchemaInterface
    {
        return $this->endpoint->getSchemas()->get($name);
    }
}
