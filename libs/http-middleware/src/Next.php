<?php

declare(strict_types=1);

namespace Railt\Http\Middleware;

use Railt\Contracts\Http\Middleware\MiddlewareInterface;
use Railt\Contracts\Http\Middleware\RequestHandlerInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\Http\Middleware
 */
final class Next implements RequestHandlerInterface
{
    public function __construct(
        private readonly MiddlewareInterface $middleware,
        private readonly RequestHandlerInterface $next,
    ) {}

    /**
     * @throws \Throwable
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        return $this->middleware->process($request, $this->next);
    }
}
