<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Type;

use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class NamedTypeNode extends TypeNode
{
    public function __construct(
        #[Visitable]
        public IdentifierNode $name,
    ) {}
}
