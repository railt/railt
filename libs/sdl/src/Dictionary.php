<?php

declare(strict_types=1);

namespace Railt\SDL;

use Railt\TypeSystem\DirectiveDefinition;
use Railt\TypeSystem\DirectivesProviderInterface;
use Railt\TypeSystem\EnumTypeDefinition;
use Railt\TypeSystem\InputObjectTypeDefinition;
use Railt\TypeSystem\NamedTypeDefinition;
use Railt\TypeSystem\ObjectLikeTypeDefinition;
use Railt\TypeSystem\SchemaDefinition;

final class Dictionary implements DictionaryInterface
{
    /**
     * @var array<non-empty-string, NamedTypeDefinition>
     */
    private array $types = [];

    /**
     * @var array<non-empty-string, DirectiveDefinition>
     */
    private array $directives = [];

    /**
     * @param iterable<NamedTypeDefinition> $types
     * @param iterable<DirectiveDefinition> $directives
     */
    public function __construct(
        private ?SchemaDefinition $schema = null,
        iterable $types = [],
        iterable $directives = [],
    ) {
        foreach ($types as $type) {
            $this->addTypeDefinition($type);
        }

        foreach ($directives as $directive) {
            $this->addDirectiveDefinition($directive);
        }
    }

    public static function fromDictionary(DictionaryInterface $dictionary): self
    {
        return new self(
            schema: $dictionary->findSchemaDefinition(),
            types: $dictionary->getTypeDefinitions(),
            directives: $dictionary->getDirectiveDefinitions(),
        );
    }

    public function findSchemaDefinition(): ?SchemaDefinition
    {
        return $this->schema;
    }

    public function hasSchemaDefinition(): bool
    {
        return $this->schema !== null;
    }

    public function setSchemaDefinition(SchemaDefinition $schema): void
    {
        $this->schema = $schema;
    }

    public function getTypeDefinitions(): iterable
    {
        return \array_values($this->types);
    }

    public function hasTypeDefinition(string $name): bool
    {
        return isset($this->types[$name]);
    }

    public function findTypeDefinition(string $name): ?NamedTypeDefinition
    {
        return $this->types[$name] ?? null;
    }

    public function addTypeDefinition(NamedTypeDefinition $type): void
    {
        $this->types[$type->getName()] = $type;
    }

    public function getDirectiveDefinitions(): iterable
    {
        return \array_values($this->directives);
    }

    public function hasDirectiveDefinition(string $name): bool
    {
        return isset($this->directives[$name]);
    }

    public function findDirectiveDefinition(string $name): ?DirectiveDefinition
    {
        return $this->directives[$name] ?? null;
    }

    public function addDirectiveDefinition(DirectiveDefinition $directive): void
    {
        $this->directives[$directive->getName()] = $directive;
    }

    public function getDirectives(string $name = null): iterable
    {
        if ($this->schema !== null) {
            foreach ($this->schema->getDirectives($name) as $directive) {
                yield $directive => $this->schema;
            }
        }

        foreach ($this->types as $type) {
            if ($type instanceof DirectivesProviderInterface) {
                foreach ($type->getDirectives($name) as $directive) {
                    yield $directive => $type;
                }
            }

            switch (true) {
                case $type instanceof EnumTypeDefinition:
                    foreach ($type->getValues() as $value) {
                        foreach ($type->getDirectives($name) as $directive) {
                            yield $directive => $value;
                        }
                    }
                    break;

                case $type instanceof InputObjectTypeDefinition:
                    foreach ($type->getFields() as $field) {
                        foreach ($type->getDirectives($name) as $directive) {
                            yield $directive => $field;
                        }
                    }
                    break;

                case $type instanceof ObjectLikeTypeDefinition:
                    foreach ($type->getFields() as $field) {
                        foreach ($type->getDirectives($name) as $directive) {
                            yield $directive => $field;
                        }

                        foreach ($field->getArguments() as $argument) {
                            foreach ($type->getDirectives($name) as $directive) {
                                yield $directive => $argument;
                            }
                        }
                    }
                    break;
            }
        }
    }
}
