<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression;

use Railt\SDL\Attribute\Visitable;
use Railt\SDL\Node\Expression\Literal\LiteralNode;
use Railt\SDL\Node\IdentifierNode;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class ArgumentNode extends Expression
{
    public function __construct(
        #[Visitable]
        public IdentifierNode $name,
        #[Visitable]
        public LiteralNode $value,
    ) {}
}