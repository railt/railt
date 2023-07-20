<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Factory;

use Railt\Contracts\Http\ResponseInterface;

interface ResponseFactoryInterface
{
    /**
     * Creates a new GraphQL response instance from the given parameters.
     *
     * @param iterable<\Throwable> $exceptions
     */
    public function createResponse(?array $data = null, iterable $exceptions = []): ResponseInterface;

    /**
     * Creates a new empty GraphQL response.
     */
    public function createEmptyResponse(): ResponseInterface;

    /**
     * Creates a new successful GraphQL response from arbitrary data.
     */
    public function createSuccessfulResponse(?array $data = null): ResponseInterface;

    /**
     * Creates a new failure GraphQL response from arbitrary exception or
     * non-empty exceptions list.
     *
     * @param iterable<\Throwable>|\Throwable $exceptions
     */
    public function createFailedResponse(iterable|\Throwable $exceptions): ResponseInterface;
}
