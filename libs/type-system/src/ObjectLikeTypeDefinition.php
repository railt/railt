<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

abstract class ObjectLikeTypeDefinition extends NamedTypeDefinition implements
    OutputTypeInterface,
    FieldDefinitionProviderInterface
{
    use FieldDefinitionProviderTrait;

    /**
     * @var array<non-empty-string, InterfaceTypeDefinition>
     */
    private array $interfaces = [];

    /**
     * @param iterable<InterfaceTypeDefinition> $interfaces
     */
    public function setInterfaces(iterable $interfaces): void
    {
        $this->interfaces = [];

        foreach ($interfaces as $interface) {
            $this->addInterface($interface);
        }
    }

    /**
     * @param iterable<InterfaceTypeDefinition> $interfaces
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

    public function addInterface(InterfaceTypeDefinition $interface): void
    {
        $this->interfaces[$interface->getName()] = $interface;
    }

    public function withAddedInterface(InterfaceTypeDefinition $interface): self
    {
        $self = clone $this;
        $self->addInterface($interface);

        return $self;
    }

    /**
     * @param InterfaceTypeDefinition|non-empty-string $interface
     */
    public function removeInterface(InterfaceTypeDefinition|string $interface): void
    {
        if ($interface instanceof InterfaceTypeDefinition) {
            $interface = $interface->getName();
        }

        unset($this->interfaces[$interface]);
    }

    /**
     * @param InterfaceTypeDefinition|non-empty-string $interface
     */
    public function withoutInterface(InterfaceTypeDefinition|string $interface): self
    {
        $self = clone $this;
        $self->removeInterface($interface);

        return $self;
    }

    /**
     * @param non-empty-string $name
     */
    public function getInterface(string $name): ?InterfaceTypeDefinition
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
     * @return iterable<InterfaceTypeDefinition>
     */
    public function getInterfaces(): iterable
    {
        return $this->interfaces;
    }
}
