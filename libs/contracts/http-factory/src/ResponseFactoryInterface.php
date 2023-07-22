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
}
