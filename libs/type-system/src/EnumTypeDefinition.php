<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class EnumTypeDefinition extends NamedTypeDefinition implements
    InputTypeInterface,
    OutputTypeInterface,
    EnumValueDefinitionProviderInterface
{
    use EnumValueDefinitionProviderTrait;

    public function __toString(): string
    {
        return \sprintf('enum<%s>', $this->getName());
    }
}
