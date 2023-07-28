<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder\Internal;

use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\Type;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\Executor\Webonyx
 */
final class Registry
{
    /**
     * @var array<non-empty-string, Type>
     */
    private array $types = [];

    /**
     * @var array<non-empty-string, Directive>
     */
    private array $directives = [];

    /**
     * @param non-empty-string $name
     */
    public function hasType(string $name): bool
    {
        return isset($this->types[$name]);
    }

    /**
     * @param non-empty-string $name
     */
    public function getType(string $name): Type
    {
        return $this->types[$name]
            ?? throw new \OutOfRangeException(
                \sprintf('Type "%s" not registered', $name)
            );
    }

    /**
     * @psalm-suppress PropertyTypeCoercion
     */
    public function setType(Type $type): void
    {
        $this->types[$type->name] = $type;
    }

    /**
     * @param non-empty-string $name
     */
    public function hasDirective(string $name): bool
    {
        return isset($this->directives[$name]);
    }

    /**
     * @param non-empty-string $name
     */
    public function getDirective(string $name): Directive
    {
        return $this->directives[$name]
            ?? throw new \OutOfRangeException(
                \sprintf('Directive "@%s" not registered', $name)
            );
    }

    /**
     * @psalm-suppress PropertyTypeCoercion
     */
    public function setDirective(Directive $type): void
    {
        $this->directives[$type->name] = $type;
    }
}
