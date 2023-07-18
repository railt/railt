<?php

declare(strict_types=1);

namespace Railt\SDL;

use Railt\TypeSystem\DirectiveDefinition;
use Railt\TypeSystem\NamedTypeDefinitionDefinition;
use Railt\TypeSystem\SchemaDefinition;

final class Dictionary implements DictionaryInterface
{
    /**
     * @var array<non-empty-string, NamedTypeDefinitionDefinition>
     */
    private array $types = [];

    /**
     * @var array<non-empty-string, DirectiveDefinition>
     */
    private array $directives = [];

    /**
     * @param iterable<NamedTypeDefinitionDefinition> $types
     * @param iterable<DirectiveDefinition> $directives
     */
    public function __construct(
        private ?SchemaDefinition $schema = null,
        iterable $types = [],
        iterable $directives = [],
    ) {
        foreach ($types as $type) {
            $this->addType($type);
        }

        foreach ($directives as $directive) {
            $this->addDirective($directive);
        }
    }

    public static function fromDictionary(DictionaryInterface $dictionary): self
    {
        return new self(
            schema: $dictionary->findSchema(),
            types: $dictionary->getTypes(),
            directives: $dictionary->getDirectives(),
        );
    }

    public function findSchema(): ?SchemaDefinition
    {
        return $this->schema;
    }

    public function hasSchema(): bool
    {
        return $this->schema !== null;
    }

    public function setSchema(SchemaDefinition $schema): void
    {
        $this->schema = $schema;
    }

    public function getTypes(): iterable
    {
        return \array_values($this->types);
    }

    public function hasType(string $name): bool
    {
        return isset($this->types[$name]);
    }

    public function findType(string $name): ?NamedTypeDefinitionDefinition
    {
        return $this->types[$name] ?? null;
    }

    public function addType(NamedTypeDefinitionDefinition $type): void
    {
        $this->types[$type->getName()] = $type;
    }

    public function getDirectives(): iterable
    {
        return \array_values($this->directives);
    }

    public function hasDirective(string $name): bool
    {
        return isset($this->directives[$name]);
    }

    public function findDirective(string $name): ?DirectiveDefinition
    {
        return $this->directives[$name] ?? null;
    }

    public function addDirective(DirectiveDefinition $directive): void
    {
        $this->directives[$directive->getName()] = $directive;
    }
}
