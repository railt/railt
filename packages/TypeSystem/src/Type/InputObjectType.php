<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\InputFieldInterface;
use GraphQL\Contracts\TypeSystem\Type\InputObjectTypeInterface;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
final class InputObjectType extends NamedType implements InputObjectTypeInterface
{
    /**
     * @psalm-var array<string, InputFieldInterface>
     * @var array|InputFieldInterface[]
     */
    protected array $fields = [];

    /**
     * InputObjectType constructor.
     *
     * @param string $name
     * @param iterable $properties
     * @throws \Throwable
     */
    public function __construct(string $name, iterable $properties = [])
    {
        parent::__construct($name, $properties);

        $this->fill($properties, [
            'fields' => fn(iterable $fields) => $this->addFields($fields),
        ]);
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param iterable|InputFieldInterface[] $fields
     * @return void
     */
    public function addFields(iterable $fields): void
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param InputFieldInterface $field
     * @return void
     */
    public function addField(InputFieldInterface $field): void
    {
        if (isset($this->fields[$field->getName()])) {
            throw new TypeUniquenessException(\sprintf('InputField %s already has been defined', $field->getName()));
        }

        $this->fields[$field->getName()] = $field;
    }

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
    public function getField(string $name): ?InputFieldInterface
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getFields(): iterable
    {
        return $this->fields;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param iterable|InputFieldInterface[] $fields
     * @return object|self|$this
     */
    public function withFields(iterable $fields): self
    {
        return Immutable::execute(fn() => $this->addFields($fields));
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param InputFieldInterface $field
     * @return object|self|$this
     */
    public function withField(InputFieldInterface $field): self
    {
        return Immutable::execute(fn() => $this->addField($field));
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
}
