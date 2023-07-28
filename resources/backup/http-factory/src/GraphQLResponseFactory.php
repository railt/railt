<?php

declare(strict_types=1);

namespace Railt\Http\Factory;

use Railt\Contracts\Http\Factory\ResponseFactoryInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Http\GraphQLResponse;

final class GraphQLResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(?array $data = null, iterable $exceptions = []): ResponseInterface
    {
        return new GraphQLResponse($data, $exceptions);
    }
}
