<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

/**
 * Tagging interface for GraphQL definitions (schema, types, directive, etc.).
 *
 * Note: Implementing the {@see \JsonSerializable} interface is an alternative
 * to the `toJSON()` method, which is defined in the reference JS
 * implementation.
 */
interface DefinitionInterface extends \Stringable, \JsonSerializable
{
    /**
     * @return array<non-empty-string, mixed>
     */
    public function jsonSerialize(): array;

    /**
     * Definition string representation.
     *
     * @return non-empty-string
     */
    public function __toString(): string;
}
