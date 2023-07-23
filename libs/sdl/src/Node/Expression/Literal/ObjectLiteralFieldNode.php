<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

use Railt\SDL\Node\Expression\Expression;
use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class ObjectLiteralFieldNode extends LiteralNode
{
    public function __construct(
        #[Visitable]
        public IdentifierNode $key,
        #[Visitable]
        public Expression $value,
    ) {}

    public function __toString(): string
    {
        return \vsprintf('%s: %s', [
            $this->key->value,
            (string)$this->value,
        ]);
    }
}
