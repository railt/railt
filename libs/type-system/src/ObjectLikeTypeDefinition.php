<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

abstract class ObjectLikeTypeDefinition extends NamedTypeDefinitionDefinition implements
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

    public function removeInterfaces(): void
    {
        $this->interfaces = [];
    }

    public function addInterface(InterfaceTypeDefinition $interface): void
    {
        $this->interfaces[$interface->getName()] = $interface;
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
     * @param non-empty-string $name
     */
    public function hasInterface(string $name): bool
    {
        return isset($this->interfaces[$name]);
    }
}
