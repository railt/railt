<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Type;

use Railt\TypeSystem\Definition\Common\HasFieldsInterface;
use Railt\TypeSystem\Definition\Common\HasFieldsTrait;
use Railt\TypeSystem\Definition\NamedTypeDefinition;
use Railt\TypeSystem\OutputTypeInterface;

abstract class ObjectLikeType extends NamedTypeDefinition implements
    OutputTypeInterface,
    HasFieldsInterface
{
    use HasFieldsTrait;

    /**
     * @var array<non-empty-string, InterfaceType>
     */
    private array $interfaces = [];

    /**
     * @param iterable<InterfaceType> $interfaces
     */
    public function setInterfaces(iterable $interfaces): void
    {
        $this->interfaces = [];

        foreach ($interfaces as $interface) {
            $this->addInterface($interface);
        }
    }

    /**
     * @param iterable<InterfaceType> $interfaces
     */
    public function withInterfaces(iterable $interfaces): self
    {
        $self = clone $this;
        $self->setInterfaces($interfaces);

        return $self;
    }

    public function removeInterfaces(): void
    {
        $this->interfaces = [];
    }

    public function withoutInterfaces(): self
    {
        $self = clone $this;
        $self->removeInterfaces();

        return $self;
    }

    public function addInterface(InterfaceType $interface): void
    {
        $this->interfaces[$interface->getName()] = $interface;
    }

    public function withAddedInterface(InterfaceType $interface): self
    {
        $self = clone $this;
        $self->addInterface($interface);

        return $self;
    }

    /**
     * @param InterfaceType|non-empty-string $interface
     */
    public function removeInterface(InterfaceType|string $interface): void
    {
        if ($interface instanceof InterfaceType) {
            $interface = $interface->getName();
        }

        unset($this->interfaces[$interface]);
    }

    /**
     * @param InterfaceType|non-empty-string $interface
     */
    public function withoutInterface(InterfaceType|string $interface): self
    {
        $self = clone $this;
        $self->removeInterface($interface);

        return $self;
    }

    /**
     * @param non-empty-string $name
     */
    public function getInterface(string $name): ?InterfaceType
    {
        return $this->interfaces[$name] ?? null;
    }

    /**
     * @return int<0, max>
     */
    public function getNumberOfInterfaces(): int
    {
        /** @var int<0, max> */
        return \count($this->interfaces);
    }

    /**
     * @param non-empty-string $name
     */
    public function hasInterface(string $name): bool
    {
        return isset($this->interfaces[$name]);
    }

    /**
     * @param non-empty-string $name
     */
    public function implements(string $name): bool
    {
        if ($this->hasInterface($name)) {
            return true;
        }

        foreach ($this->interfaces as $interface) {
            if ($interface->implements($name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return iterable<InterfaceType>
     */
    public function getInterfaces(): iterable
    {
        return $this->interfaces;
    }
}
