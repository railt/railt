<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Factory;

use Railt\Contracts\Http\Factory\Exception\ParsingExceptionInterface;
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

    /**
     * Creates a new empty GraphQL request.
     */
    public function createEmptyRequest(): RequestInterface;

    /**
     * Creates a new GraphQL request from associative array payload.
     *
     * @param array{
     *  query?: string,
     *  variables?: array<non-empty-string, mixed>,
     *  operationName?: non-empty-string|null,
     *  ...
     * } $data
     *
     * @throws ParsingExceptionInterface
     */
    public function createRequestFromArray(array $data): RequestInterface;
}
