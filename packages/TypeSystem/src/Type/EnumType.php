<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\EnumValueInterface;
use GraphQL\Contracts\TypeSystem\Type\EnumTypeInterface;
use Railt\Common\Iter;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
class EnumType extends NamedType implements EnumTypeInterface
{
    /**
     * @psalm-var array<string, EnumValueInterface>
     * @var array|EnumValueInterface[]
     */
    protected array $values = [];

    /**
     * {@inheritDoc}
     */
    public function hasValue(string $name): bool
    {
        return $this->getValue($name) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(string $name): ?EnumValueInterface
    {
        return $this->values[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getValues(): iterable
    {
        return $this->values;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param iterable|EnumValueInterface[] $values
     * @return void
     */
    public function setValues(iterable $values): void
    {
        $this->values = Iter::mapToArray($values, static function (EnumValueInterface $value): array {
            return [$value->getName() => $value];
        });
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param iterable|EnumValueInterface[] $values
     * @return object|self|$this
     */
    public function withValues(iterable $values): self
    {
        return Immutable::execute(fn() => $this->setValues($values));
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param EnumValueInterface $value
     * @return object|self|$this
     */
    public function withValue(EnumValueInterface $value): self
    {
        return Immutable::execute(fn() => $this->addValue($value));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param EnumValueInterface $value
     * @return void
     */
    public function addValue(EnumValueInterface $value): void
    {
        $this->values[$value->getName()] = $value;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param string $name
     * @return object|self|$this
     */
    public function withoutValue(string $name): self
    {
        return Immutable::execute(fn() => $this->removeValue($name));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param string $name
     * @return void
     */
    public function removeValue(string $name): void
    {
        unset($this->values[$name]);
    }
}
