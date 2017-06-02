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
use Serafim\Railgun\Schema\Creators\CreatorInterface;
use Serafim\Railgun\Schema\Creators\FieldDefinitionCreator;
use Serafim\Railgun\Schema\Definitions\{
    ArgumentDefinitionInterface, FieldDefinition, FieldDefinitionInterface, TypeDefinitionInterface
};
use Serafim\Railgun\Types\TypeInterface;

/**
 * Class CreatorsBuilder
 * @package Serafim\Railgun\Adapters\Webonyx\Builders
 */
class CreatorsBuilder
{
    use BuilderHelpers;

    /**
     * @var TypeBuilder
     */
    private $types;

    /**
     * DefinitionsBuilder constructor.
     * @param TypeBuilder $builder
     */
    public function __construct(TypeBuilder $builder)
    {
        $this->types = $builder;
    }

    /**
     * @param iterable $creators
     * @return array|Type[]|array[]
     * @throws \InvalidArgumentException
     */
    public function buildCollectionOf(iterable $creators): array
    {
        $result = [];

        foreach ($creators as $field => $creator) {
            switch (true) {
                case is_int($field):
                    $result[] = $this->build($creator);
                    break;

                case is_string($field) || method_exists($field, '__toString'):
                    $field = (string)$field; // Invoke __toString() method
                    $result[$field] = $this->build($creator);
                    break;

                default:
                    $message = 'Iterator key must be type of int or string. %s given.';
                    throw new \InvalidArgumentException(sprintf($message, gettype($field)));
            }
        }

        return $result;
    }

    /**
     * @param CreatorInterface $creator
     * @return Type|array
     * @throws \InvalidArgumentException
     */
    public function build(CreatorInterface $creator)
    {
        $definition = $creator->build();


        switch (true) {
            case $definition instanceof TypeDefinitionInterface:
                return $this->buildTypeDefinition($definition);

            case $definition instanceof FieldDefinitionInterface:
                return $this->buildFieldDefinition($definition);

            case $definition instanceof ArgumentDefinitionInterface:
                return $this->buildArgumentDefinition($definition);
        }

        $message = 'Invalid creator or definition type %s';
        throw new \InvalidArgumentException(sprintf($message, get_class($creatorOrDefinition)));
    }

    /**
     * @param TypeDefinitionInterface $definition
     * @return Type
     * @throws \InvalidArgumentException
     */
    public function buildTypeDefinition(TypeDefinitionInterface $definition): Type
    {
        /** @var TypeInterface $type */
        $type = $this->types->type($definition->getTypeName());

        /** @var Type $webonyx */
        $webonyx = $this->types->build($type);

        if ($definition->isList()) {
            $webonyx = Type::listOf($webonyx);
        }

        return $definition->isNullable() ? $webonyx : Type::nonNull($webonyx);
    }

    /**
     * @param FieldDefinitionInterface $definition
     * @param null|string $name
     * @return array
     * @throws \InvalidArgumentException
     */
    public function buildFieldDefinition(FieldDefinitionInterface $definition, ?string $name = null): array
    {
        $result = [
            'name'        => $name ?? $definition->getName(),
            'description' => $definition->getDescription(),
            'type'        => $this->buildTypeDefinition($definition->getTypeDefinition()),
            'args'        => [],
            // TODO
            // 'resolve' => function($value, array $arguments, $context) { ... }
        ];

        $result = static::withDeprecation($definition, $result);

        return $result;
    }

    /**
     * @param ArgumentDefinitionInterface $definition
     * @return array
     */
    public function buildArgumentDefinition(ArgumentDefinitionInterface $definition): array
    {
        // TODO
        return [];
    }

    /**
     * @param iterable $fields
     * @return array
     * @throws \InvalidArgumentException
     */
    public function buildFields(iterable $fields): array
    {
        $result = [];

        /** @var FieldDefinitionCreator $creator */
        foreach ($fields as $name => $creator) {
            /** @var FieldDefinition $definition */
            $definition = $creator->build();

            $result[] = $this->buildFieldDefinition($definition, $this->resolveName($name, $definition));
        }

        return $result;
    }

    /**
     * @param string|int $name
     * @param FieldDefinition $definition
     * @return null|string
     */
    private function resolveName($name, FieldDefinition $definition): ?string
    {
        if (is_string($name)) {
            return $name;
        }

        return $definition->hasName() ? $definition->getName() : null;
    }
}
