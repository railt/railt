<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class EnumValueDefinition extends Definition implements NamedDefinitionInterface
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return \sprintf('enum-value<%s>', $this->getName());
    }
}
