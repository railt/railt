<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use GraphQL\Contracts\TypeSystem\Common\InterfacesAwareInterface;
use Railt\Common\Iter;
use Serafim\Immutable\Immutable;

/**
 * @mixin InterfacesAwareInterface
 */
trait InterfacesTrait
{
    /**
     * @psalm-var array<string, InterfaceTypeInterface>
     * @var array|InterfaceTypeInterface[]
     */
    protected array $interfaces = [];

    /**
     * {@inheritDoc}
     */
    public function hasInterface(string $name): bool
    {
        return $this->getInterface($name) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getInterface(string $name): ?InterfaceTypeInterface
    {
        return $this->interfaces[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function getInterfaces(): iterable
    {
        return $this->interfaces;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param iterable|InterfaceTypeInterface[] $interfaces
     * @return void
     */
    public function setInterfaces(iterable $interfaces): void
    {
        $this->interfaces = Iter::mapToArray($interfaces, static function (InterfaceTypeInterface $type): array {
            return [$type->getName() => $type];
        });
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param iterable|InterfaceTypeInterface[] $interfaces
     * @return object|self|$this
     */
    public function withInterfaces(iterable $interfaces): self
    {
        return Immutable::execute(fn() => $this->setInterfaces($interfaces));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param InterfaceTypeInterface $interface
     * @return void
     */
    public function addInterface(InterfaceTypeInterface $interface): void
    {
        $this->interfaces[$interface->getName()] = $interface;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param InterfaceTypeInterface $interface
     * @return object|self|$this
     */
    public function withInterface(InterfaceTypeInterface $interface): self
    {
        return Immutable::execute(fn() => $this->addInterface($interface));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param string $name
     * @return void
     */
    public function removeInterface(string $name): void
    {
        unset($this->interfaces[$name]);
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param string $name
     * @return object|self|$this
     */
    public function withoutInterface(string $name): self
    {
        return Immutable::execute(fn() => $this->removeInterface($name));
    }
}
