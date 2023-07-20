<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Request;

interface QueryProviderInterface
{
    /**
     * Returns GraphQL query string.
     */
    public function getQuery(): string;

    /**
     * Returns new instance of {@see QueryProviderInterface} with the passed
     * query argument.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  query argument.
     */
    public function withQuery(string $query): self;

    /**
     * Returns {@see true} in case of GraphQL request is empty
     * or {@see false} instead.
     */
    public function isEmpty(): bool;
}
