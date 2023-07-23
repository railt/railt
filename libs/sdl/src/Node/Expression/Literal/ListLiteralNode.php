<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class ListLiteralNode extends LiteralNode
{
    /**
     * @param list<LiteralNode> $value
     */
    public function __construct(
        public array $value,
    ) {}

    public function __toString(): string
    {
        $result = [];

        foreach ($this->value as $literal) {
            $result[] = (string)$literal;
        }

        return \vsprintf('[%s]', [
            \implode(', ', $result),
        ]);
    }
}
