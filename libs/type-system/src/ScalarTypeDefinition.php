<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class ScalarTypeDefinition extends NamedTypeDefinitionDefinition implements
    InputTypeInterface,
    OutputTypeInterface
{
    /**
     * @var non-empty-string|null
     */
    private ?string $specificationUrl = null;

    /**
     * @param non-empty-string $url
     */
    public function setSpecificationUrl(string $url): void
    {
        $this->specificationUrl = $url;
    }

    public function removeSpecificationUrl(): void
    {
        $this->specificationUrl = null;
    }

    public function __toString(): string
    {
        return \sprintf('scalar<%s>', $this->getName());
    }
}
