<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class FloatLiteralNode extends LiteralNode
{
    public function __construct(
        public float $value,
    ) {}

    /**
     * @param numeric-string $value
     */
    public static function parse(string $value): self
    {
        return new self((float)$value);
    }
}
