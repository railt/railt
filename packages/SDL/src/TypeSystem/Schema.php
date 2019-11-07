<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem;

use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\UnionTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\AbstractTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;

/**
 * {@inheritDoc}
 */
class Schema extends Definition implements SchemaInterface
{
    /**
     * @var ObjectTypeInterface|null
     */
    public ?ObjectTypeInterface $query;

    /**
     * @var ObjectTypeInterface|null
     */
    public ?ObjectTypeInterface $mutation;

    /**
     * @var ObjectTypeInterface|null
     */
    public ?ObjectTypeInterface $subscription;

    /**
     * @psalm-var array<string, NamedTypeInterface>
     * @var array|NamedTypeInterface[]
     */
    public array $typeMap = [];

    /**
     * @psalm-var array<string, DirectiveInterface>
     * @var array|DirectiveInterface[]
     */
    public array $directives = [];

    /**
     * {@inheritDoc}
     */
    public function getQueryType(): ?ObjectTypeInterface
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     */
    public function getMutationType(): ?ObjectTypeInterface
    {
        return $this->mutation;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscriptionType(): ?ObjectTypeInterface
    {
        return $this->subscription;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(string $name): ?NamedTypeInterface
    {
        return $this->typeMap[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getPossibleTypes(AbstractTypeInterface $abstract): iterable
    {
        foreach ($this->getTypeMap() as $name => $type) {
            if ($type instanceof ObjectTypeInterface && $this->isPossibleType($abstract, $type)) {
                yield $name => $type;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeMap(): iterable
    {
        return $this->typeMap;
    }

    /**
     * {@inheritDoc}
     */
    public function isPossibleType(AbstractTypeInterface $abstract, ObjectTypeInterface $possible): bool
    {
        switch (true) {
            case $abstract instanceof InterfaceTypeInterface:
                return $possible->hasInterface($abstract->getName());

            case $abstract instanceof UnionTypeInterface:
                return $abstract->hasType($abstract->getName());

            default:
                return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDirectives(): iterable
    {
        return $this->directives;
    }

    /**
     * {@inheritDoc}
     */
    public function getDirective(string $name): ?DirectiveInterface
    {
        return $this->directives[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return 'Schema';
    }
}
