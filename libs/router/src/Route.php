<?php

declare(strict_types=1);

namespace Railt\Router;

final class Route
{
    /**
     * @param list<\ReflectionParameter> $parameters
     * @param non-empty-string|null $on
     */
    public function __construct(
        public readonly \Closure $handler,
        public readonly array $parameters = [],
        public readonly ?string $on = null,
    ) {}
}
