<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Factory;

use Railt\Contracts\Http\RequestInterface;

interface RequestFactoryInterface
{
    /**
     * Creates a new GraphQL request instance from the given parameters.
     *
     * @param array<non-empty-string, mixed> $variables
     * @param non-empty-string|null $operationName
     */
    public function createRequest(string $query, array $variables = [], ?string $operationName = null): RequestInterface;
}
