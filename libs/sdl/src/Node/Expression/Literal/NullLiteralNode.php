<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class NullLiteralNode extends LiteralNode
{
    public function __toString(): string
    {
        return 'null';
    }
}
