<?php

declare(strict_types=1);

namespace Railt\SDL;

use Railt\TypeSystem\Directive;
use Railt\TypeSystem\DirectiveDefinition;
use Railt\TypeSystem\DirectivesProviderInterface;
use Railt\TypeSystem\NamedTypeDefinition;
use Railt\TypeSystem\SchemaDefinition;

interface DictionaryInterface
{
    /**
     * @return SchemaDefinition|null
     */
    public function findSchemaDefinition(): ?SchemaDefinition;

    /**
     * @return bool
     */
    public function hasSchemaDefinition(): bool;

    /**
     * @return iterable<NamedTypeDefinition>
     */
    public function getTypeDefinitions(): iterable;

    /**
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
     * @return iterable<Directive, DirectivesProviderInterface>
     */
    public function getDirectives(string $name = null): iterable;
}
