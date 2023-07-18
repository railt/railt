<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class InputObjectTypeDefinition extends NamedTypeDefinitionDefinition implements
    InputTypeInterface,
    InputFieldDefinitionProviderInterface
{
    use InputFieldDefinitionProviderTrait;

    public function __toString(): string
    {
        return \sprintf('input<%s>', $this->getName());
    }
}
