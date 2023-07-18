<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class UnionTypeDefinition extends NamedTypeDefinitionDefinition implements
    OutputTypeInterface
{
    /**
     * @var array<non-empty-string, ObjectTypeDefinition>
     */
    private array $types = [];

    /**
     * @param iterable<ObjectTypeDefinition> $types
     */
    public function setTypes(iterable $types): void
    {
        $this->types = [];

        foreach ($types as $type) {
            $this->addType($type);
        }
    }

    public function removeTypes(): void
    {
        $this->types = [];
    }

    public function addType(ObjectTypeDefinition $type): void
    {
        $this->types[$type->getName()] = $type;
    }

    /**
     * @param ObjectTypeDefinition|non-empty-string $type
     */
    public function removeType(ObjectTypeDefinition|string $type): void
    {
        if ($type instanceof ObjectTypeDefinition) {
            $type = $type->getName();
        }

        unset($this->types[$type]);
    }

    public function __toString(): string
    {
        return \sprintf('union<%s>', $this->getName());
    }
}
