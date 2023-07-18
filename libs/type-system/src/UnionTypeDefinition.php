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

    /**
     * @param iterable<ObjectTypeDefinition> $types
     */
    public function withTypes(iterable $types): self
    {
        $self = clone $this;
        $self->setTypes($types);

        return $self;
    }

    public function removeTypes(): void
    {
        $this->types = [];
    }

    public function withoutTypes(): self
    {
        $self = clone $this;
        $self->removeTypes();

        return $self;
    }

    public function addType(ObjectTypeDefinition $type): void
    {
        $this->types[$type->getName()] = $type;
    }

    public function withAddedType(ObjectTypeDefinition $type): self
    {
        $self = clone $this;
        $self->addType($type);

        return $self;
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

    /**
     * @param ObjectTypeDefinition|non-empty-string $type
     */
    public function withoutType(ObjectTypeDefinition|string $type): self
    {
        $self = clone $this;
        $self->removeType($type);

        return $self;
    }

    /**
     * @param non-empty-string $name
     */
    public function getType(string $name): ?ObjectTypeDefinition
    {
        return $this->types[$name] ?? null;
    }

    /**
     * @return int<0, max>
     */
    public function getNumberOfTypes(): int
    {
        /** @var int<0, max> */
        return \count($this->types);
    }

    /**
     * @param non-empty-string $name
     */
    public function hasType(string $name): bool
    {
        return isset($this->types[$name]);
    }

    /**
     * @return iterable<ObjectTypeDefinition>
     */
    public function getTypes(): iterable
    {
        return $this->types;
    }

    public function __toString(): string
    {
        return \sprintf('union<%s>', $this->getName());
    }
}
