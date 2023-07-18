<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

abstract class NamedTypeDefinition extends Definition implements
    NamedTypeDefinitionInterface
{
    use DescriptionAwareTrait;
    use DeprecationAwareTrait;
    use DirectivesProviderTrait;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        private readonly string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
