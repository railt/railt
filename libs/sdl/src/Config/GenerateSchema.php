<?php

declare(strict_types=1);

namespace Railt\SDL\Config;

final class GenerateSchema
{
    /**
     * @param non-empty-string|null $queryTypeName
     * @param non-empty-string|null $mutationTypeName
     * @param non-empty-string|null $subscriptionTypeName
     */
    public function __construct(
        public readonly ?string $queryTypeName = 'Query',
        public readonly ?string $mutationTypeName = 'Mutation',
        public readonly ?string $subscriptionTypeName = 'Subscription',
    ) {}
}
