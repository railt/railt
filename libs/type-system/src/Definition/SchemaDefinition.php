<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition;

use Railt\TypeSystem\Common\HasDescriptionInterface;
use Railt\TypeSystem\Common\HasDescriptionTrait;
use Railt\TypeSystem\Definition;
use Railt\TypeSystem\Definition\Type\ObjectTypeDefinition;
use Railt\TypeSystem\Execution\Common\HasDirectivesInterface;
use Railt\TypeSystem\Execution\Common\HasDirectivesTrait;

final class SchemaDefinition extends Definition implements
    HasDirectivesInterface,
    HasDescriptionInterface
{
    use HasDescriptionTrait;
    use HasDirectivesTrait;

    public function __construct(
        private ?ObjectTypeDefinition $query = null,
        private ?ObjectTypeDefinition $mutation = null,
        private ?ObjectTypeDefinition $subscription = null,
    ) {
    }

    public function setQueryType(ObjectTypeDefinition $query): void
    {
        $this->query = $query;
    }

    public function withQueryType(ObjectTypeDefinition $query): self
    {
        $self = clone $this;
        $self->setQueryType($query);

        return $self;
    }

    public function removeQueryType(): void
    {
        $this->query = null;
    }

    public function withoutQueryType(): self
    {
        $self = clone $this;
        $self->removeQueryType();

        return $self;
    }

    public function getQueryType(): ?ObjectTypeDefinition
    {
        return $this->query;
    }

    public function setMutationType(ObjectTypeDefinition $mutation): void
    {
        $this->mutation = $mutation;
    }

    public function withMutationType(ObjectTypeDefinition $mutation): self
    {
        $self = clone $this;
        $self->setMutationType($mutation);

        return $self;
    }

    public function removeMutationType(): void
    {
        $this->mutation = null;
    }

    public function withoutMutationType(): self
    {
        $self = clone $this;
        $self->removeMutationType();

        return $self;
    }

    public function getMutationType(): ?ObjectTypeDefinition
    {
        return $this->mutation;
    }

    public function setSubscriptionType(ObjectTypeDefinition $subscription): void
    {
        $this->subscription = $subscription;
    }

    public function withSubscriptionType(ObjectTypeDefinition $subscription): self
    {
        $self = clone $this;
        $self->setSubscriptionType($subscription);

        return $self;
    }

    public function removeSubscriptionType(): void
    {
        $this->subscription = null;
    }

    public function withoutSubscriptionType(): self
    {
        $self = clone $this;
        $self->removeSubscriptionType();

        return $self;
    }

    public function getSubscriptionType(): ?ObjectTypeDefinition
    {
        return $this->subscription;
    }

    public function __toString(): string
    {
        return 'schema';
    }
}
