<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

abstract class NamedTypeDefinitionDefinition extends Definition implements
    NamedTypeDefinitionInterface
{
    use DescriptionAwareTrait;
    use DeprecationAwareTrait;
    use DirectivesProviderTrait;

    public function getName(): string
    {
        return $this->name;
    }
}
