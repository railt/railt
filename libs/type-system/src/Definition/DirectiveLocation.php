<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition;

use Railt\TypeSystem\Definition\Type\EnumTypeDefinition;
use Railt\TypeSystem\Definition\Type\InputObjectTypeDefinition;
use Railt\TypeSystem\Definition\Type\InterfaceTypeDefinition;
use Railt\TypeSystem\Definition\Type\ObjectTypeDefinition;
use Railt\TypeSystem\Definition\Type\ScalarTypeDefinition;
use Railt\TypeSystem\Definition\Type\UnionTypeDefinition;
use Railt\TypeSystem\DefinitionInterface;

enum DirectiveLocation implements DirectiveLocationInterface
{
    #[DirectiveLocationInformation(isExecutable: true)]
    case QUERY;

    #[DirectiveLocationInformation(isExecutable: true)]
    case MUTATION;

    #[DirectiveLocationInformation(isExecutable: true)]
    case SUBSCRIPTION;

    #[DirectiveLocationInformation(isExecutable: true)]
    case FIELD;

    #[DirectiveLocationInformation(isExecutable: true)]
    case FRAGMENT_DEFINITION;

    #[DirectiveLocationInformation(isExecutable: true)]
    case FRAGMENT_SPREAD;

    #[DirectiveLocationInformation(isExecutable: true)]
    case INLINE_FRAGMENT;

    #[DirectiveLocationInformation(isExecutable: true)]
    case VARIABLE_DEFINITION;

    #[DirectiveLocationInformation(ref: SchemaDefinition::class)]
    case SCHEMA;

    #[DirectiveLocationInformation(ref: ScalarTypeDefinition::class)]
    case SCALAR;

    #[DirectiveLocationInformation(ref: ObjectTypeDefinition::class)]
    case OBJECT;

    #[DirectiveLocationInformation(ref: FieldDefinition::class)]
    case FIELD_DEFINITION;

    #[DirectiveLocationInformation(ref: ArgumentDefinition::class)]
    case ARGUMENT_DEFINITION;

    #[DirectiveLocationInformation(ref: InterfaceTypeDefinition::class)]
    case INTERFACE;

    #[DirectiveLocationInformation(ref: UnionTypeDefinition::class)]
    case UNION;

    #[DirectiveLocationInformation(ref: EnumTypeDefinition::class)]
    case ENUM;

    #[DirectiveLocationInformation(ref: EnumValueDefinition::class)]
    case ENUM_VALUE;

    #[DirectiveLocationInformation(ref: InputObjectTypeDefinition::class)]
    case INPUT_OBJECT;

    #[DirectiveLocationInformation(ref: InputFieldDefinition::class)]
    case INPUT_FIELD_DEFINITION;

    public static function tryFromName(string $name): ?self
    {
        try {
            /** @var DirectiveLocation */
            return (new \ReflectionEnumUnitCase(self::class, $name))
                ->getValue();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    private function getInformation(): DirectiveLocationInformation
    {
        /** @var array<non-empty-string, DirectiveLocationInformation> $memory */
        static $memory = [];

        if (isset($memory[$this->name])) {
            return $memory[$this->name];
        }

        $attributes = (new \ReflectionEnumUnitCase(self::class, $this->name))
            ->getAttributes(DirectiveLocationInformation::class);

        if (isset($attributes[0])) {
            return $memory[$this->name] = $attributes[0]->newInstance();
        }

        return new DirectiveLocationInformation();
    }

    public function isExecutable(): bool
    {
        $metadata = $this->getInformation();

        return $metadata->isExecutable;
    }

    public function isTypeSystem(): bool
    {
        $metadata = $this->getInformation();

        return !$metadata->isExecutable;
    }

    public function isAvailableFor(DefinitionInterface $definition): bool
    {
        $metadata = $this->getInformation();

        if ($metadata->ref === null) {
            return false;
        }

        return $definition instanceof $metadata->ref;
    }
}
