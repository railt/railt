<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

use Railt\SDL\Attribute\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class ObjectLiteralNode extends LiteralNode
{
    /**
     * @param list<ObjectLiteralFieldNode> $fields
     */
    public function __construct(
        #[Visitable]
        public array $fields = [],
    ) {}
}
