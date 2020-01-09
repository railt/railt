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
use Railt\Common\Iter;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
class Schema extends Definition implements SchemaInterface
{
    /**
     * @var ObjectTypeInterface|null
     */
    protected ?ObjectTypeInterface $query = null;

    /**
     * @var ObjectTypeInterface|null
     */
    protected ?ObjectTypeInterface $mutation = null;

    /**
     * @var ObjectTypeInterface|null
     */
    protected ?ObjectTypeInterface $subscription = null;

    /**
     * @psalm-var array<string, NamedTypeInterface>
     * @var array|NamedTypeInterface[]
     */
    protected array $typeMap = [];

    /**
     * @psalm-var array<string, DirectiveInterface>
     * @var array|DirectiveInterface[]
     */
    protected array $directives = [];

    /**
     * {@inheritDoc}
     */
    public function getQueryType(): ?ObjectTypeInterface
    {
        return $this->query;
    }

    /**
     * @internal This is an alias of Schema::withQueryType() method.
     * @psalm-return self
     *
     * @param ObjectTypeInterface|null $object
     * @return object|self|$this
     */
    public function withQuery(?ObjectTypeInterface $object): self
    {
        return $this->withQueryType($object);
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param ObjectTypeInterface|null $object
     * @return object|self|$this
     */
    public function withQueryType(?ObjectTypeInterface $object): self
    {
        return Immutable::execute(fn() => $this->setQuery($object));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param ObjectTypeInterface|null $object
     * @return void
     */
    public function setQuery(?ObjectTypeInterface $object): void
    {
        if (($this->query = $object) && ! $this->hasType($this->query->getName())) {
            $this->addType($this->query);
        }
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
    public function getMutationType(): ?ObjectTypeInterface
    {
        return $this->mutation;
    }

    /**
     * @internal This is an alias of Schema::withMutationType() method.
     * @psalm-return self
     *
     * @param ObjectTypeInterface|null $object
     * @return object|self|$this
     */
    public function withMutation(?ObjectTypeInterface $object): self
    {
        return $this->withMutationType($object);
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param ObjectTypeInterface|null $object
     * @return object|self|$this
     */
    public function withMutationType(?ObjectTypeInterface $object): self
    {
        return Immutable::execute(fn() => $this->setMutation($object));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param ObjectTypeInterface|null $object
     * @return void
     */
    public function setMutation(?ObjectTypeInterface $object): void
    {
        if (($this->mutation = $object) && ! $this->hasType($this->mutation->getName())) {
            $this->addType($this->mutation);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscriptionType(): ?ObjectTypeInterface
    {
        return $this->subscription;
    }

    /**
     * @internal This is an alias of Schema::withSubscriptionType() method.
     * @psalm-return self
     *
     * @param ObjectTypeInterface|null $object
     * @return object|self|$this
     */
    public function withSubscription(?ObjectTypeInterface $object): self
    {
        return $this->withSubscriptionType($object);
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param ObjectTypeInterface|null $object
     * @return object|self|$this
     */
    public function withSubscriptionType(?ObjectTypeInterface $object): self
    {
        return Immutable::execute(fn() => $this->setSubscription($object));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param ObjectTypeInterface|null $object
     * @return void
     */
    public function setSubscription(?ObjectTypeInterface $object): void
    {
        if (($this->subscription = $object) && ! $this->hasType($this->subscription->getName())) {
            $this->addType($this->subscription);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getType(string $name): ?NamedTypeInterface
    {
        return $this->typeMap[$name] ?? null;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param NamedTypeInterface $type
     * @return object|self|$this
     */
    public function withType(NamedTypeInterface $type): self
    {
        return Immutable::execute(fn() => $this->addType($type));
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
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
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param iterable|NamedTypeInterface[] $typeMap
     * @return void
     */
    public function setTypeMap(iterable $typeMap): void
    {
        $this->typeMap = Iter::mapToArray($typeMap, static function (NamedTypeInterface $type): array {
            return [$type->getName() => $type];
        });
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
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param iterable|NamedTypeInterface[] $typeMap
     * @return object|self|$this
     */
    public function withTypeMap(iterable $typeMap): self
    {
        return Immutable::execute(fn() => $this->setTypeMap($typeMap));
    }

    /**
     * {@inheritDoc}
     */
    public function getDirectives(): iterable
    {
        return $this->directives;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param iterable|DirectiveInterface[] $directives
     * @return void
     */
    public function setDirectives(iterable $directives): void
    {
        $this->directives = Iter::mapToArray($directives, static function (DirectiveInterface $directive): array {
            return [$directive->getName() => $directive];
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getDirective(string $name): ?DirectiveInterface
    {
        return $this->directives[$name] ?? null;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param iterable|DirectiveInterface[] $directives
     * @return object|self|$this
     */
    public function withDirectives(iterable $directives): self
    {
        return Immutable::execute(fn() => $this->setDirectives($directives));
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param DirectiveInterface $directive
     * @return object|self|$this
     */
    public function withDirective(DirectiveInterface $directive): self
    {
        return Immutable::execute(fn() => $this->addDirective($directive));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param DirectiveInterface $directive
     * @return void
     */
    public function addDirective(DirectiveInterface $directive): void
    {
        $this->directives[$directive->getName()] = $directive;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
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
                $context = $this->$property;

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
