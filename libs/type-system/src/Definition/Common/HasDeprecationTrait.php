<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Common;

/**
 * @mixin HasDeprecationInterface
 * @psalm-require-implements HasDeprecationInterface
 */
trait HasDeprecationTrait
{
    private ?string $deprecationReason = null;

    public function setDeprecationReason(string $reason): void
    {
        $this->deprecationReason = $reason;
    }

    public function removeDeprecationReason(): void
    {
        $this->deprecationReason = null;
    }

    public function withDeprecationReason(string $reason): self
    {
        $self = clone $this;
        $self->setDeprecationReason($reason);

        return $self;
    }

    public function withoutDeprecationReason(): self
    {
        $self = clone $this;
        $self->removeDeprecationReason();

        return $self;
    }

    public function getDeprecationReason(): ?string
    {
        return $this->deprecationReason;
    }

    public function isDeprecated(): bool
    {
        return $this->deprecationReason !== null;
    }
}
