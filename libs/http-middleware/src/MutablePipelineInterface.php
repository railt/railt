<?php

declare(strict_types=1);

namespace Railt\Http\Middleware;

use Railt\Contracts\Http\Middleware\MiddlewareInterface;

interface MutablePipelineInterface extends PipelineInterface
{
    /**
     * Mutable equivalent of {@see PipelineInterface::withAppendedMiddleware()} method.
     *
     * @link PipelineInterface::withAppendedMiddleware() method description.
     *
     * @param null|callable(MiddlewareInterface):bool $after
     */
    public function appendMiddleware(MiddlewareInterface $middleware, callable $after = null): void;

    /**
     * Mutable equivalent of {@see PipelineInterface::withPrependedMiddleware()} method.
     *
     * @link PipelineInterface::withPrependedMiddleware() method description.
     *
     * @param null|callable(MiddlewareInterface):bool $before
     */
    public function prependMiddleware(MiddlewareInterface $middleware, callable $before = null): void;

    /**
     * Mutable equivalent of {@see PipelineInterface::withMiddleware()} method.
     *
     * @link PipelineInterface::withMiddleware() method description.
     *
     * @param iterable<MiddlewareInterface> $middleware
     */
    public function setMiddleware(iterable $middleware): void;

    /**
     * Mutable equivalent of {@see PipelineInterface::withoutMiddleware()} method.
     *
     * @link PipelineInterface::withoutMiddleware() method description.
     */
    public function removeMiddleware(MiddlewareInterface $middleware): void;
}
