<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\TypeSystem\Definition\DirectiveDefinition;
use Railt\TypeSystem\Definition\NamedTypeDefinition;
use Railt\TypeSystem\Definition\SchemaDefinition;
use Railt\TypeSystem\Execution\Common\HasDirectivesInterface;
use Railt\TypeSystem\Execution\Directive;

/**
 * Contains a list of registered GraphQL types.
 */
interface DictionaryInterface
{
    /**
     * Returns the schema object if it has been registered.
     */
    public function findSchemaDefinition(): ?SchemaDefinition;

    /**
     * Returns {@see true} in the case that the schema object
     * has been registered or {@see false} instead.
     */
    public function hasSchemaDefinition(): bool;

    /**
     * Returns list of the registered GraphQL type definitions.
     *
     * @return iterable<NamedTypeDefinition>
     */
    public function getTypeDefinitions(): iterable;

    /**
     * Returns {@see true} in case of the GraphQL type definition
     * with the given name has been registered or {@see false} instead.
     *
     * @param non-empty-string $name
     */
    public function hasTypeDefinition(string $name): bool;

    /**
     * @param non-empty-string $name
     */
    public function findTypeDefinition(string $name): ?NamedTypeDefinition;

    /**
     * @return iterable<DirectiveDefinition>
     */
    public function getDirectiveDefinitions(): iterable;

    /**
     * @param non-empty-string $name
     */
    public function hasDirectiveDefinition(string $name): bool;

    /**
     * @param non-empty-string $name
     */
    public function findDirectiveDefinition(string $name): ?DirectiveDefinition;

    /**
     * @param non-empty-string|null $name
     *
     * @return iterable<HasDirectivesInterface, Directive>
     */
    public function getDirectives(string $name = null): iterable;
}
