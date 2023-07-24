<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Middleware;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;

/**
 * Participant in processing a GraphQL request and GraphQL response.
 *
 * A GraphQL middleware component participates in processing a GraphQL message:
 * by acting on the request, generating the response, or forwarding the
 * request to a subsequent middleware and possibly acting on its response.
 */
interface MiddlewareInterface
{
    /**
     * Process an incoming GraphQL request.
     *
     * Processes an incoming GraphQL request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
