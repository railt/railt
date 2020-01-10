<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\Type\AbstractTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\UnionTypeInterface;
use Railt\TypeSystem\Collection\Directives;
use Railt\TypeSystem\Collection\TypeMap;
use Railt\TypeSystem\Reference\Reference;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
final class Schema extends Definition implements SchemaInterface
{
    /**
     * @var TypeReferenceInterface|null
     */
    protected ?TypeReferenceInterface $query = null;

    /**
     * @var TypeReferenceInterface|null
     */
    protected ?TypeReferenceInterface $mutation = null;

    /**
     * @var TypeReferenceInterface|null
     */
    protected ?TypeReferenceInterface $subscription = null;

    /**
     * @psalm-var TypeMap<string, NamedTypeInterface>
     * @var TypeMap|NamedTypeInterface[]
     */
    protected TypeMap $typeMap;

    /**
     * @psalm-var Directives<string, DirectiveInterface>
     * @var Directives|DirectiveInterface[]
     */
    protected Directives $directives;

    /**
     * Schema constructor.
     *
     * @param iterable $properties
     * @throws \Throwable
     */
    public function __construct(iterable $properties = [])
    {
        $this->typeMap = new TypeMap();
        $this->directives = new Directives();

        $this->fill($properties, [
            'query'        => fn(TypeReferenceInterface $ref) => $this->setQueryType($ref),
            'mutation'     => fn(TypeReferenceInterface $ref) => $this->setMutationType($ref),
            'subscription' => fn(TypeReferenceInterface $ref) => $this->setSubscriptionType($ref),
            'typeMap'      => fn(iterable $types) => $this->addTypes($types),
            'directives'   => fn(iterable $directives) => $this->addDirectives($directives),
        ]);
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeReferenceInterface|null $object
     * @return void
     */
    public function setQueryType(?TypeReferenceInterface $object): void
    {
        $this->query = $object;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeReferenceInterface|null $object
     * @return void
     */
    public function setMutationType(?TypeReferenceInterface $object): void
    {
        $this->mutation = $object;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeReferenceInterface|null $object
     * @return void
     */
    public function setSubscriptionType(?TypeReferenceInterface $object): void
    {
        $this->subscription = $object;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param NamedTypeInterface[] $types
     * @return void
     */
    public function addTypes(iterable $types): void
    {
        foreach ($types as $type) {
            $this->addType($type);
        }
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param NamedTypeInterface $type
     * @return void
     */
    public function addType(NamedTypeInterface $type): void
    {
        $this->typeMap[$type->getName()] = $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryType(): ?ObjectTypeInterface
    {
        return Reference::resolveNullable($this, $this->query, ObjectTypeInterface::class);
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param TypeReferenceInterface|null $object
     * @return object|self|$this
     */
    public function withQueryType(?TypeReferenceInterface $object): self
    {
        return Immutable::execute(fn() => $this->setQueryType($object));
    }

    /**
     * {@inheritDoc}
     */
    public function getMutationType(): ?ObjectTypeInterface
    {
        return Reference::resolveNullable($this, $this->mutation, ObjectTypeInterface::class);
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param TypeReferenceInterface|null $object
     * @return object|self|$this
     */
    public function withMutationType(?TypeReferenceInterface $object): self
    {
        return Immutable::execute(fn() => $this->setMutationType($object));
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscriptionType(): ?ObjectTypeInterface
    {
        return Reference::resolveNullable($this, $this->subscription, ObjectTypeInterface::class);
    }

    /**
     * @param TypeReferenceInterface|null $object
     * @return object|self|$this
     */
    public function withSubscriptionType(?TypeReferenceInterface $object): self
    {
        return Immutable::execute(fn() => $this->setSubscriptionType($object));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool
    {
        return isset($this->typeMap[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getType(string $name): ?NamedTypeInterface
    {
        return $this->typeMap[$name] ?? null;
    }

    /**
     * @param NamedTypeInterface $type
     * @return object|self|$this
     */
    public function withType(NamedTypeInterface $type): self
    {
        return Immutable::execute(fn() => $this->addType($type));
    }

    /**
     * @param NamedTypeInterface[] $types
     * @return object|self|$this
     */
    public function withTypes(iterable $types): self
    {
        return Immutable::execute(fn() => $this->addTypes($types));
    }

    /**
     * @param string $name
     * @return object|self|$this
     */
    public function withoutType(string $name): self
    {
        return Immutable::execute(fn() => $this->removeType($name));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param string $name
     * @return void
     */
    public function removeType(string $name): void
    {
        unset($this->typeMap[$name]);
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
     * @param DirectiveInterface $directive
     * @return object|self|$this
     */
    public function withDirective(DirectiveInterface $directive): self
    {
        return Immutable::execute(fn() => $this->addDirective($directive));
    }

    /**
     * @param DirectiveInterface $directive
     * @return void
     */
    public function addDirective(DirectiveInterface $directive): void
    {
        $this->directives[$directive->getName()] = $directive;
    }

    /**
     * @param DirectiveInterface[] $directives
     * @return object|self|$this
     */
    public function withDirectives(iterable $directives): self
    {
        return Immutable::execute(fn() => $this->addDirectives($directives));
    }

    /**
     * @param DirectiveInterface[] $directives
     * @return void
     */
    public function addDirectives(iterable $directives): void
    {
        foreach ($directives as $directive) {
            $this->addDirective($directive);
        }
    }

    /**
     * @param string $name
     * @return object|self|$this
     */
    public function withoutDirective(string $name): self
    {
        return Immutable::execute(fn() => $this->removeDirective($name));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param string $name
     * @return void
     */
    public function removeDirective(string $name): void
    {
        unset($this->directives[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        $chunks = [];

        foreach (['query', 'mutation', 'subscription'] as $property) {
            if (\property_exists($this, $property) && $this->$property !== null) {
                /** @var ObjectTypeInterface $context */
                $context = Reference::resolve($this, $this->$property, ObjectTypeInterface::class);

                /**
                 * @psalm-suppress MixedMethodCall
                 * @psalm-suppress MixedOperand
                 */
                $chunks[] = $property . ': ' . $context->getName();
            }
        }

        return \sprintf('schema { %s }', \implode(', ', $chunks));
    }
}
