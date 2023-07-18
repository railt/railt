<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

/**
 * @mixin DescriptionAwareInterface
 * @psalm-require-implements DescriptionAwareInterface
 */
trait DescriptionAwareTrait
{
    private ?string $description = null;

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function removeDescription(): void
    {
        $this->description = null;
    }

    public function withDescription(string $description): self
    {
        $self = clone $this;
        $self->setDescription($description);

        return $self;
    }

    public function withoutDescription(): self
    {
        $self = clone $this;
        $self->removeDescription();

        return $self;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
