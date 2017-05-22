<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\Type;
use Serafim\Railgun\Adapters\Webonyx\BuilderInterface;
use Serafim\Railgun\Adapters\Webonyx\Support\IterablesBuilder;
use Serafim\Railgun\Adapters\Webonyx\Support\NameBuilder;
use Serafim\Railgun\Contracts\Partials\ArgumentTypeInterface;
use Serafim\Railgun\Contracts\Partials\EnumValueTypeInterface;
use Serafim\Railgun\Contracts\Partials\FieldTypeInterface;
use Serafim\Railgun\Contracts\Partials\MutationTypeInterface;
use Serafim\Railgun\Contracts\Partials\QueryTypeInterface;
use Serafim\Railgun\Contracts\SchemaInterface;
use Serafim\Railgun\Contracts\TypeDefinitionInterface;
use Serafim\Railgun\Contracts\Types\TypeInterface;
use Serafim\Railgun\Types\Schemas\Arguments;
use Serafim\Railgun\Types\Schemas\TypeDefinition;

/**
 * Class PartialsBuilder
 * @package Serafim\Railgun\Adapters\Webonyx\Builders
 */
class PartialsBuilder
{
    use NameBuilder;
    use IterablesBuilder;

    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * PartialsBuilder constructor.
     * @param BuilderInterface $builder
     */
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param TypeInterface $type
     * @param null|string $name
     * @return array
     * @throws \InvalidArgumentException
     */
    public function build(TypeInterface $type, ?string $name = null): array
    {
        switch (true) {
            case $type instanceof QueryTypeInterface:
                return $this->makeQueryType($type, $name);

            case $type instanceof MutationTypeInterface:
                return $this->makeMutationType($type, $name);

            case $type instanceof FieldTypeInterface:
                return $this->makeFieldType($type, $name);

            case $type instanceof EnumValueTypeInterface:
                return $this->makeEnumValueType($type, $name);

            case $type instanceof ArgumentTypeInterface:
                return $this->makeArgumentType($type, $name);
        }

        throw new \InvalidArgumentException('Invalid type definition for: ' . get_class($type));
    }

    /**
     * @param QueryTypeInterface $query
     * @param null|string $name
     * @return array
     * @throws \InvalidArgumentException
     */
    private function makeQueryType(QueryTypeInterface $query, ?string $name): array
    {
        return $this->makeFieldType($query, $name);
    }

    /**
     * @param FieldTypeInterface $field
     * @param null|string $name
     * @return array
     * @throws \InvalidArgumentException
     */
    private function makeFieldType(FieldTypeInterface $field, ?string $name): array
    {
        $data = [
            'type' => $this->makeTypeDefinition(
                $field->getType($this->getTypeDefinitionSchema())
            ),

            'args' => $this->makeIterable(
                $field->getArguments($this->getArgumentsSchema())
            ),
        ];

        if ($field->isDeprecated()) {
            $data['deprecationReason'] = $field->getDeprecationReason();
        }

        if ($field->isResolvable()) {
            $data['resolve'] = [$field, 'resolve'];
        }

        return array_merge($this->makeName($field, $name), $data);
    }

    /**
     * @return TypeDefinition|SchemaInterface
     */
    private function getTypeDefinitionSchema(): TypeDefinition
    {
        return $this->builder->getRegistry()->schema(TypeDefinition::class);
    }

    /**
     * @return Arguments|SchemaInterface
     */
    private function getArgumentsSchema(): Arguments
    {
        return $this->builder->getRegistry()->schema(Arguments::class);
    }

    /**
     * @param MutationTypeInterface $mutation
     * @param null|string $name
     * @return array
     * @throws \InvalidArgumentException
     */
    private function makeMutationType(MutationTypeInterface $mutation, ?string $name): array
    {
        return $this->makeFieldType($mutation, $name);
    }

    /**
     * @param EnumValueTypeInterface $enumValue
     * @param null|string $name
     * @return array
     */
    private function makeEnumValueType(EnumValueTypeInterface $enumValue, ?string $name): array
    {
        return array_merge($this->makeName($enumValue, $name), [
            'value' => $enumValue->getValue(),
        ]);
    }

    /**
     * @param ArgumentTypeInterface $argument
     * @param null|string $name
     * @return array
     * @throws \InvalidArgumentException
     */
    private function makeArgumentType(ArgumentTypeInterface $argument, ?string $name): array
    {
        $data = [
            'type'         => $this->makeTypeDefinition(
                $argument->getType($this->getTypeDefinitionSchema())
            ),
            'defaultValue' => $argument->getDefaultValue(),
        ];

        return array_merge($this->makeName($argument, $name), $data);
    }

    /**
     * @param TypeDefinitionInterface $definition
     * @return Type
     * @throws \InvalidArgumentException
     */
    private function makeTypeDefinition(TypeDefinitionInterface $definition): Type
    {
        return $this->builder->getTypesBuilder()->buildTypeDefinition($definition);
    }
}
