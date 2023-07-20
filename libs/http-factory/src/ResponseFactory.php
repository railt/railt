<?php

declare(strict_types=1);

namespace Railt\Http\Factory;

use Railt\Contracts\Http\Factory\ResponseFactoryInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Http\Response;

final class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(?array $data = null, iterable $exceptions = []): ResponseInterface
    {
        return new Response($data, $exceptions);
    }

    public function createSuccessfulResponse(?array $data = null): ResponseInterface
    {
        return $this->createResponse($data);
    }

    public function createFailedResponse(iterable|\Throwable $exceptions): ResponseInterface
    {
        if ($exceptions instanceof \Throwable) {
            $exceptions = [$exceptions];
        }

        return $this->createResponse(null, $exceptions);
    }

    public function createEmptyResponse(): ResponseInterface
    {
        return $this->createResponse();
    }
}
