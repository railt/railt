<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class EnumValueDefinition extends Definition implements
    NamedDefinitionInterface,
    DeprecationAwareInterface
{
    use DeprecationAwareTrait;
    use DescriptionAwareTrait;
    use DirectivesProviderTrait;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        private readonly string $name,
        private mixed $value = null,
    ) {
    }

    /**
     * @param non-empty-string $name
     */
    public static function fromName(string $name): self
    {
        return new self($name, $name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function withValue(mixed $value): self
    {
        $self = clone $this;
        $self->setValue($value);

        return $self;
    }

    public function __toString(): string
    {
        return \sprintf('enum-value<%s>', $this->getName());
    }
}
