<?php

declare(strict_types=1);

namespace Railt\Router;

final class Route
{
    public function __construct(
        public readonly \Closure $handler,
        public readonly ?string $on = null,
    ) {}
}
