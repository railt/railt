<?php

declare(strict_types=1);

namespace Railt\SDL\Node;

use Railt\SDL\Node\Expression\Literal\StringLiteralNode;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class DescriptionNode extends Node
{
    public function __construct(
        #[Visitable]
        public ?StringLiteralNode $value = null,
    ) {}
}
