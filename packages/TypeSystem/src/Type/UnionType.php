<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\UnionTypeInterface;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
final class UnionType extends NamedType implements UnionTypeInterface
{
    /**
     * @var string
     */
    private const ERROR_TYPE_UNIQUENESS = 'Union "%s" must contain only one type named "%s"';

    /**
     * @psalm-var array<string, TypeReferenceInterface>
     * @var array|TypeReferenceInterface[]
     */
    protected array $types = [];

    /**
     * UnionType constructor.
     *
     * @param string $name
     * @param iterable $properties
     * @throws \Throwable
     */
    public function __construct(string $name, iterable $properties = [])
    {
        parent::__construct($name, $properties);

        $this->fill($properties, [
            'types' => fn(iterable $types) => $this->addTypes($types),
        ]);
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeReferenceInterface[] $types
     * @return void
     */
    public function addTypes(iterable $types): void
    {
        foreach ($types as $ref) {
            $this->addType($ref);
        }
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeReferenceInterface $type
     * @return void
     */
    public function addType(TypeReferenceInterface $type): void
    {
        if (isset($this->types[$type->getName()])) {
            $message = \sprintf(self::ERROR_TYPE_UNIQUENESS, $this->getName(), $type->getName());

            throw new TypeUniquenessException($message);
        }

        $this->types[$type->getName()] = $type;
    }

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
        return Reference::resolveNullable($this, $this->types[$name] ?? null, ObjectTypeInterface::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypes(): iterable
    {
        foreach ($this->types as $ref) {
            /** @var ObjectTypeInterface $object */
            $object = Reference::resolve($this, $ref, ObjectTypeInterface::class);

            yield $object->getName() => $object;
        }
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param TypeReferenceInterface[] $types
     * @return object|self|$this
     */
    public function withTypes(iterable $types): self
    {
        return Immutable::execute(fn() => $this->addTypes($types));
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param TypeReferenceInterface $type
     * @return object|self|$this
     */
    public function withType(TypeReferenceInterface $type): self
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
        unset($this->types[$name]);
    }
}
