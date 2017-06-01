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
use Serafim\Railgun\Types\TypeInterface;
use Serafim\Railgun\Schema\Creators\CreatorInterface;
use Serafim\Railgun\Schema\Definitions\TypeDefinitionInterface;
use Serafim\Railgun\Schema\Definitions\FieldDefinitionInterface;
use Serafim\Railgun\Schema\Definitions\ArgumentDefinitionInterface;
use Serafim\Railgun\Schema\Definitions\ProvidesTypeDefinitionInterface;

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
     * @param CreatorInterface|TypeDefinitionInterface|ProvidesTypeDefinitionInterface $creatorOrDefinition
     * @return Type|array
     * @throws \InvalidArgumentException
     */
    public function build($creatorOrDefinition)
    {
        $definition = $creatorOrDefinition instanceof CreatorInterface
            ? $creatorOrDefinition->build()
            : $creatorOrDefinition;


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
     * @return array
     * @throws \InvalidArgumentException
     */
    public function buildFieldDefinition(FieldDefinitionInterface $definition): array
    {
        return static::withInfo($definition, static::withDeprecation($definition, [
            'type' => $this->buildTypeDefinition($definition->getTypeDefinition()),
            'args' => $this->buildCollectionOf($definition->getArguments()),
            // TODO
            // 'resolve' => function($value, array $arguments, $context) { ... }
        ]));
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
}
