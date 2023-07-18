<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

interface ExpressionInterface extends \Stringable, \JsonSerializable
{
    /**
     * @return array<non-empty-string, mixed>
     */
    public function jsonSerialize(): array;

    /**
     * An alternative to the `toString()` and `inspect()` methods, which is
     * described in the reference implementation.
     *
     * {@inheritDoc}
     */
    public function __toString(): string;
}
