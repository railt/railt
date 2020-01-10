<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Common\InterfacesAwareInterface;
use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Serafim\Immutable\Immutable;

/**
 * @mixin InterfacesAwareInterface
 */
trait InterfacesTrait
{
    /**
     * @psalm-var array<string, TypeReferenceInterface>
     * @var array|TypeReferenceInterface[]
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
        $type = $this->interfaces[$name] ?? null;

        return Reference::resolveNullable($this, $type, InterfaceTypeInterface::class);
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function getInterfaces(): iterable
    {
        foreach ($this->interfaces as $ref) {
            /** @var InterfaceTypeInterface $interface */
            $interface = Reference::resolve($this, $ref, InterfaceTypeInterface::class);

            yield $interface->getName() => $interface;
        }
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param TypeReferenceInterface[] $interfaces
     * @return object|self|$this
     */
    public function withInterfaces(iterable $interfaces): self
    {
        return Immutable::execute(fn() => $this->addInterfaces($interfaces));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeReferenceInterface[] $interfaces
     * @return void
     */
    public function addInterfaces(iterable $interfaces): void
    {
        foreach ($interfaces as $ref) {
            $this->addInterface($ref);
        }
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeReferenceInterface $interface
     * @return void
     */
    public function addInterface(TypeReferenceInterface $interface): void
    {
        if (isset($this->interfaces[$interface->getName()])) {
            throw new TypeUniquenessException(\sprintf('Interface %s already has been defined', $interface->getName()));
        }

        $this->interfaces[$interface->getName()] = $interface;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param TypeReferenceInterface $interface
     * @return object|self|$this
     */
    public function withInterface(TypeReferenceInterface $interface): self
    {
        return Immutable::execute(fn() => $this->addInterface($interface));
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
}
