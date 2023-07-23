<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

/**
 * @mixin HasDescriptionInterface
 * @psalm-require-implements HasDescriptionInterface
 */
trait HasDescriptionTrait
{
    protected ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @psalm-suppress MethodSignatureMismatch
     */
    public function withDescription(string $description): self
    {
        $self = clone $this;
        $self->setDescription($description);

        return $self;
    }

    public function removeDescription(): void
    {
        $this->description = null;
    }

    /**
     * @psalm-suppress MethodSignatureMismatch
     */
    public function withoutDescription(): self
    {
        $self = clone $this;
        $self->removeDescription();

        return $self;
    }
}
