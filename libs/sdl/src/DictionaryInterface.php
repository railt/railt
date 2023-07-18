<?php

declare(strict_types=1);

namespace Railt\SDL;

use Railt\TypeSystem\DirectiveDefinition;
use Railt\TypeSystem\NamedTypeDefinitionDefinition;
use Railt\TypeSystem\SchemaDefinition;

interface DictionaryInterface
{
    /**
     * @return SchemaDefinition|null
     */
    public function findSchema(): ?SchemaDefinition;

    /**
     * @return bool
     */
    public function hasSchema(): bool;

    /**
     * @return iterable<NamedTypeDefinitionDefinition>
     */
    public function getTypes(): iterable;

    /**
     * @param non-empty-string $name
     */
    public function hasType(string $name): bool;

    /**
     * @param non-empty-string $name
     */
    public function findType(string $name): ?NamedTypeDefinitionDefinition;

    /**
     * @return iterable<DirectiveDefinition>
     */
    public function getDirectives(): iterable;

    /**
     * @param non-empty-string $name
     */
    public function hasDirective(string $name): bool;

    /**
     * @param non-empty-string $name
     */
    public function findDirective(string $name): ?DirectiveDefinition;
}
