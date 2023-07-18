<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class InputFieldDefinition extends Definition implements NamedDefinitionInterface
{
    use DescriptionAwareTrait;
    use DeprecationAwareTrait;
    use DirectivesProviderTrait;

    private mixed $defaultValue = null;
    private bool $hasDefaultValue = false;

    public function setDefaultValue(mixed $value): void
    {
        $this->defaultValue = $value;
        $this->hasDefaultValue = true;
    }

    public function removeDefaultValue(): void
    {
        $this->defaultValue = null;
        $this->hasDefaultValue = false;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): InputTypeInterface
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return \sprintf('input-field<%s>', $this->getName());
    }
}
