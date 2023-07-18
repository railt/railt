<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class VariableLiteralNode extends LiteralNode
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
    ) {}
}
