<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Middleware;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;

/**
 * A GraphQL request handler process a GraphQL request in order to produce an
 * GraphQL response.
 */
interface RequestHandlerInterface
{
    /**
     * Handles a GraphQL request and produces a GraphQL response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(RequestInterface $request): ResponseInterface;
}
