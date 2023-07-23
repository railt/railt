<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Common;

use Railt\TypeSystem\Definition\InputFieldDefinition;

/**
 * @mixin HasInputFieldsInterface
 * @psalm-require-implements HasInputFieldsInterface
 */
trait HasInputFieldsTrait
{
    /**
     * @var array<non-empty-string, InputFieldDefinition>
     */
    private array $fields = [];

    /**
     * @param iterable<InputFieldDefinition> $fields
     */
    public function setFields(iterable $fields): void
    {
        $this->fields = [];

        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    /**
     * @param iterable<InputFieldDefinition> $fields
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

    public function addField(InputFieldDefinition $field): void
    {
        $this->fields[$field->getName()] = $field;
    }

    public function withAddedField(InputFieldDefinition $field): self
    {
        $self = clone $this;
        $self->addField($field);

        return $self;
    }

    /**
     * @param InputFieldDefinition|non-empty-string $field
     */
    public function removeField(InputFieldDefinition|string $field): void
    {
        if ($field instanceof InputFieldDefinition) {
            $field = $field->getName();
        }

        unset($this->fields[$field]);
    }

    /**
     * @param InputFieldDefinition|non-empty-string $field
     */
    public function withoutField(InputFieldDefinition|string $field): self
    {
        $self = clone $this;
        $self->removeField($field);

        return $self;
    }

    public function getField(string $name): ?InputFieldDefinition
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
     * @return list<InputFieldDefinition>
     */
    public function getFields(): array
    {
        return \array_values($this->fields);
    }
}
