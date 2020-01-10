<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Common\FieldsAwareInterface;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use Railt\Common\Iter;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Serafim\Immutable\Immutable;

/**
 * @mixin FieldsAwareInterface
 */
trait FieldsTrait
{
    /**
     * @psalm-var array<string, FieldInterface>
     * @var array|FieldInterface[]
     */
    protected array $fields = [];

    /**
     * {@inheritDoc}
     */
    public function hasField(string $name): bool
    {
        return $this->getField($name) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getField(string $name): ?FieldInterface
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function getFields(): iterable
    {
        return $this->fields;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param iterable|FieldInterface[] $fields
     * @return void
     */
    public function addFields(iterable $fields): void
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param iterable|FieldInterface[] $fields
     * @return object|self|$this
     */
    public function withFields(iterable $fields): self
    {
        return Immutable::execute(fn() => $this->addFields($fields));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param FieldInterface $field
     * @return void
     */
    public function addField(FieldInterface $field): void
    {
        if (isset($this->fields[$field->getName()])) {
            $message = \sprintf('Field %s has already been defined', $field->getName());

            throw new TypeUniquenessException($message);
        }

        $this->fields[$field->getName()] = $field;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param FieldInterface $field
     * @return object|self|$this
     */
    public function withField(FieldInterface $field): self
    {
        return Immutable::execute(fn() => $this->addField($field));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param string $name
     * @return void
     */
    public function removeField(string $name): void
    {
        unset($this->fields[$name]);
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param string $name
     * @return object|self|$this
     */
    public function withoutField(string $name): self
    {
        return Immutable::execute(fn() => $this->removeField($name));
    }
}
