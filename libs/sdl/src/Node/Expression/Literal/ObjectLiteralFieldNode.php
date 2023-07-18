<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

use Railt\SDL\Attribute\Visitable;
use Railt\SDL\Node\IdentifierNode;

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
        public LiteralNode $value,
    ) {}
}