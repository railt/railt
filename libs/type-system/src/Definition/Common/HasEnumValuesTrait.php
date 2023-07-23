<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Common;

use Railt\TypeSystem\Definition\EnumValueDefinition;

/**
 * @mixin HasEnumValuesInterface
 * @psalm-require-implements HasEnumValuesInterface
 */
trait HasEnumValuesTrait
{
    /**
     * @var array<non-empty-string, EnumValueDefinition>
     */
    private array $values = [];

    /**
     * @param iterable<EnumValueDefinition> $values
     */
    public function setValues(iterable $values): void
    {
        $this->values = [];

        foreach ($values as $value) {
            $this->addValue($value);
        }
    }

    /**
     * @param iterable<EnumValueDefinition> $values
     */
    public function withValues(iterable $values): self
    {
        $self = clone $this;
        $self->setValues($values);

        return $self;
    }

    public function removeValues(): void
    {
        $this->values = [];
    }

    public function withoutValues(): self
    {
        $self = clone $this;
        $self->removeValues();

        return $self;
    }

    public function addValue(EnumValueDefinition $value): void
    {
        $this->values[$value->getName()] = $value;
    }

    public function withAddedValue(EnumValueDefinition $value): self
    {
        $self = clone $this;
        $self->addValue($value);

        return $self;
    }

    /**
     * @param EnumValueDefinition|non-empty-string $value
     */
    public function removeValue(EnumValueDefinition|string $value): void
    {
        if ($value instanceof EnumValueDefinition) {
            $value = $value->getName();
        }

        unset($this->values[$value]);
    }

    /**
     * @param EnumValueDefinition|non-empty-string $value
     */
    public function withoutValue(EnumValueDefinition|string $value): self
    {
        $self = clone $this;
        $self->removeValue($value);

        return $self;
    }

    public function getValue(string $name): ?EnumValueDefinition
    {
        return $this->values[$name] ?? null;
    }

    /**
     * @return int<0, max>
     */
    public function getNumberOfValues(): int
    {
        /** @var int<0, max> */
        return \count($this->values);
    }

    public function hasValue(string $name): bool
    {
        return isset($this->values[$name]);
    }

    /**
     * @return list<EnumValueDefinition>
     */
    public function getValues(): array
    {
        return \array_values($this->values);
    }
}
