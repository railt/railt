<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Type;

use Railt\TypeSystem\Definition\NamedTypeDefinition;
use Railt\TypeSystem\InputTypeInterface;
use Railt\TypeSystem\OutputTypeInterface;

class ScalarType extends NamedTypeDefinition implements
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

    /**
     * @param non-empty-string $url
     */
    public function withSpecificationUrl(string $url): self
    {
        $self = clone $this;
        $self->setSpecificationUrl($url);

        return $self;
    }

    public function removeSpecificationUrl(): void
    {
        $this->specificationUrl = null;
    }

    public function withoutSpecificationUrl(): self
    {
        $self = clone $this;
        $self->removeSpecificationUrl();

        return $self;
    }

    /**
     * An optional URI to custom scalar definitions pointing to a document
     * holding data-format, serialization, and coercion rules for the scalar.
     *
     * @see https://github.com/graphql/graphql-spec/issues/635
     *
     * @return non-empty-string|null
     */
    public function getSpecificationUrl(): ?string
    {
        return $this->specificationUrl ?: null;
    }

    public function __toString(): string
    {
        return \sprintf('scalar<%s>', $this->getName());
    }
}
