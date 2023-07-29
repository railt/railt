<?php

declare(strict_types=1);

namespace Railt\Http\Middleware;

use Railt\Contracts\Http\Middleware\MiddlewareInterface;
use Railt\Contracts\Http\Middleware\RequestHandlerInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;

final class Pipeline implements MutablePipelineInterface
{
    /**
     * @var array<non-empty-string, MiddlewareInterface>
     */
    private array $middleware = [];

    /**
     * @var \WeakMap<RequestHandlerInterface, RequestHandlerInterface>
     */
    private \WeakMap $compiled;

    /**
     * @param iterable<MiddlewareInterface> $middleware
     * @psalm-suppress PropertyTypeCoercion
     */
    public function __construct(
        iterable $middleware = [],
    ) {
        $this->compiled = new \WeakMap();

        $this->setMiddleware($middleware);
    }

    public function withAppendedMiddleware(MiddlewareInterface $middleware, callable $after = null): self
    {
        $self = clone $this;
        $self->appendMiddleware($middleware, $after);

        return $self;
    }

    public function appendMiddleware(MiddlewareInterface $middleware, callable $after = null): void
    {
        $this->reset();

        if ($after === null) {
            $this->removeMiddleware($middleware);

            $this->middleware = [
                ...$this->middleware,
                $this->keyOf($middleware) => $middleware,
            ];

            return;
        }

        $this->appendAfter($middleware, $after);
    }

    /**
     * @param callable(MiddlewareInterface):bool $after
     */
    private function appendAfter(MiddlewareInterface $middleware, callable $after): void
    {
        $result = [];

        foreach ($this->middleware as $id => $actual) {
            $result[$id] = $actual;

            if ($after($actual)) {
                $this->removeMiddleware($middleware);

                $result[$this->keyOf($middleware)] = $middleware;
            }
        }

        $this->middleware = $result;
    }

    public function withPrependedMiddleware(MiddlewareInterface $middleware, callable $before = null): self
    {
        $self = clone $this;
        $self->prependMiddleware($middleware, $before);

        return $self;
    }

    public function prependMiddleware(MiddlewareInterface $middleware, callable $before = null): void
    {
        $this->reset();

        if ($before === null) {
            $this->removeMiddleware($middleware);

            $this->middleware = [
                $this->keyOf($middleware) => $middleware,
                ...$this->middleware,
            ];

            return;
        }

        $this->prependBefore($middleware, $before);
    }

    /**
     * @param callable(MiddlewareInterface):bool $before
     */
    private function prependBefore(MiddlewareInterface $middleware, callable $before): void
    {
        $result = [];

        foreach ($this->middleware as $id => $actual) {
            if ($before($actual)) {
                $this->removeMiddleware($middleware);

                $result[$this->keyOf($middleware)] = $middleware;
            }

            $result[$id] = $actual;
        }

        $this->middleware = $result;
    }

    public function withMiddleware(iterable $middleware): PipelineInterface
    {
        $self = clone $this;
        $self->setMiddleware($middleware);

        return $self;
    }

    public function setMiddleware(iterable $middleware): void
    {
        $this->reset();

        $this->middleware = [];

        foreach ($middleware as $item) {
            $this->middleware[$this->keyOf($item)] = $item;
        }
    }

    public function withoutMiddleware(MiddlewareInterface $middleware): self
    {
        $self = clone $this;
        $self->removeMiddleware($middleware);

        return $self;
    }

    public function removeMiddleware(MiddlewareInterface $middleware): void
    {
        $this->reset();

        unset($this->middleware[$this->keyOf($middleware)]);
    }

    /**
     * @return non-empty-string
     */
    private function keyOf(MiddlewareInterface $middleware): string
    {
        /** @var non-empty-string */
        return \spl_object_hash($middleware);
    }

    /**
     * @psalm-suppress PropertyTypeCoercion
     */
    private function reset(): void
    {
        $this->compiled = new \WeakMap();
    }

    private function reduce(RequestHandlerInterface $handler): RequestHandlerInterface
    {
        if (!isset($this->compiled[$handler])) {
            $compiled = $handler;

            foreach (\array_reverse($this->middleware) as $middleware) {
                $compiled = new Next($middleware, $compiled);
            }

            $this->compiled[$handler] = $compiled;
        }

        return $this->compiled[$handler];
    }

    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $compiled = $this->reduce($handler);

        return $compiled->handle($request);
    }
}
