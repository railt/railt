<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class SchemaDefinition extends Definition implements
    DirectivesProviderInterface,
    DescriptionAwareInterface,
    DeprecationAwareInterface
{
    use DescriptionAwareTrait;
    use DeprecationAwareTrait;
    use DirectivesProviderTrait;

    public function setQueryType(ObjectTypeDefinition $query): void
    {
        $this->query = $query;
    }

    public function removeQueryType(): void
    {
        $this->query = null;
    }

    public function setMutationType(ObjectTypeDefinition $mutation): void
    {
        $this->mutation = $mutation;
    }

    public function removeMutationType(): void
    {
        $this->mutation = null;
    }

    public function setSubscriptionType(ObjectTypeDefinition $subscription): void
    {
        $this->subscription = $subscription;
    }

    public function removeSubscriptionType(): void
    {
        $this->subscription = null;
    }

    public function __toString(): string
    {
        return 'schema';
    }
}
