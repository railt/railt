<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class BoolLiteralNode extends LiteralNode
{
    public function __construct(
        public bool $value,
    ) {}

    public static function parse(string $value): self
    {
        return new self(\strtolower($value) === 'true');
    }
}
