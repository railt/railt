<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class VariableNode extends Expression
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
    ) {}

    public function __toString(): string
    {
        return '$' . $this->name;
    }
}
