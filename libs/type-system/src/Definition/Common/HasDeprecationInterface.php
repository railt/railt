<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Common;

/**
 * Interface for GraphQL definitions that can be deprecated.
 *
 * @see https://graphql.github.io/graphql-spec/draft/#sec--deprecated
 */
interface HasDeprecationInterface
{
    public function getDeprecationReason(): ?string;

    public function isDeprecated(): bool;
}
