<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

use Railt\SDL\Node\Visitable;

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

    public function __toString(): string
    {
        $result = [];

        foreach ($this->fields as $field) {
            $result[] = (string)$field;
        }

        return \vsprintf('{%s}', [
            \implode(', ', $result),
        ]);
    }
}
