<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class ConstLiteralNode extends LiteralNode
{
    public function __construct(
        #[Visitable]
        public IdentifierNode $value,
    ) {}

    public function __toString(): string
    {
        return $this->value->value;
    }
}
