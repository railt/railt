<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Common;

use Railt\TypeSystem\Definition\FieldDefinition;

/**
 * @mixin HasFieldsInterface
 * @psalm-require-implements HasFieldsInterface
 */
trait HasFieldsTrait
{
    /**
     * @var array<non-empty-string, FieldDefinition>
     */
    private array $fields = [];

    /**
     * @param iterable<FieldDefinition> $fields
     */
    public function setFields(iterable $fields): void
    {
        $this->fields = [];

        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    /**
     * @param iterable<FieldDefinition> $fields
     */
    public function withFields(iterable $fields): self
    {
        $self = clone $this;
        $self->setFields($fields);

        return $self;
    }

    public function removeFields(): void
    {
        $this->fields = [];
    }

    public function withoutFields(): self
    {
        $self = clone $this;
        $self->removeFields();

        return $self;
    }

    public function addField(FieldDefinition $field): void
    {
        $this->fields[$field->getName()] = $field;
    }

    public function withAddedField(FieldDefinition $field): self
    {
        $self = clone $this;
        $self->addField($field);

        return $self;
    }

    /**
     * @param FieldDefinition|non-empty-string $field
     */
    public function removeField(FieldDefinition|string $field): void
    {
        if ($field instanceof FieldDefinition) {
            $field = $field->getName();
        }

        unset($this->fields[$field]);
    }

    /**
     * @param FieldDefinition|non-empty-string $field
     */
    public function withoutField(FieldDefinition|string $field): self
    {
        $self = clone $this;
        $self->removeField($field);

        return $self;
    }

    public function getField(string $name): ?FieldDefinition
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * @return int<0, max>
     */
    public function getNumberOfFields(): int
    {
        /** @var int<0, max> */
        return \count($this->fields);
    }

    public function hasField(string $name): bool
    {
        return isset($this->fields[$name]);
    }

    /**
     * @return list<FieldDefinition>
     */
    public function getFields(): array
    {
        return \array_values($this->fields);
    }
}
