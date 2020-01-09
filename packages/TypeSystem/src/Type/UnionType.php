<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Type\UnionTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use Railt\Common\Iter;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
class UnionType extends NamedType implements UnionTypeInterface
{
    /**
     * @psalm-var array<string, ObjectTypeInterface>
     * @var array|ObjectTypeInterface[]
     */
    protected array $types = [];

    /**
     * {@inheritDoc}
     */
    public function hasType(string $name): bool
    {
        return $this->getType($name) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(string $name): ?ObjectTypeInterface
    {
        return $this->types[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getTypes(): iterable
    {
        return $this->types;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param iterable|ObjectTypeInterface[] $types
     * @return void
     */
    public function setTypes(iterable $types): void
    {
        $this->types = Iter::mapToArray($types, static function (ObjectTypeInterface $type): array {
            return [$type->getName() => $type];
        });
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param iterable|ObjectTypeInterface[] $types
     * @return object|self|$this
     */
    public function withTypes(iterable $types): self
    {
        return Immutable::execute(fn() => $this->setTypes($types));
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param ObjectTypeInterface $type
     * @return object|self|$this
     */
    public function withType(ObjectTypeInterface $type): self
    {
        return Immutable::execute(fn() => $this->addType($type));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param ObjectTypeInterface $type
     * @return void
     */
    public function addType(ObjectTypeInterface $type): void
    {
        $this->types[$type->getName()] = $type;
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
        unset($this->types[$name]);
    }
}
