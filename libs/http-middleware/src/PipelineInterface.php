<?php

declare(strict_types=1);

namespace Railt\Http\Middleware;

use Railt\Contracts\Http\Middleware\MiddlewareInterface;

interface PipelineInterface extends MiddlewareInterface
{
    /**
     * Return an instance with the added specified middleware at the end
     * of middleware list.
     *
     * In case of `$after` is not {@see null}, then the specified middleware
     * will be added after matched middleware.
     *
     * @param null|callable(MiddlewareInterface):bool $after
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified middleware at the end of the middleware list.
     */
    public function withAppendedMiddleware(MiddlewareInterface $middleware, callable $after = null): self;

    /**
     * Return an instance with the added specified middleware at the start
     * of middleware list.
     *
     * In case of `$before` is not {@see null}, then the specified middleware
     * will be added before matched middleware.
     *
     * @param null|callable(MiddlewareInterface):bool $before
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified middleware at the end of the middleware list.
     */
    public function withPrependedMiddleware(MiddlewareInterface $middleware, callable $before = null): self;

    /**
     * Return an instance with the specified middleware list.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified middleware list.
     *
     * @param iterable<MiddlewareInterface> $middleware
     */
    public function withMiddleware(iterable $middleware): self;

    /**
     * Return an instance without specified middleware.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that not contains
     *                  the specified middleware.
     */
    public function withoutMiddleware(MiddlewareInterface $middleware): self;

}
