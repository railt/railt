<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class DirectiveDefinition extends Definition implements
    DescriptionAwareInterface,
    ArgumentDefinitionProviderInterface
{
    use DescriptionAwareTrait;
    use ArgumentDefinitionProviderTrait;

    /**
     * @var array<non-empty-string, DirectiveLocation>
     */
    private array $locations = [];

    private bool $isRepeatable = false;

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param iterable<DirectiveLocation> $locations
     */
    public function setLocations(iterable $locations): void
    {
        $this->locations = [];

        foreach ($locations as $location) {
            $this->addLocation($location);
        }
    }

    public function removeLocations(): void
    {
        $this->locations = [];
    }

    public function addLocation(DirectiveLocation $location): void
    {
        $this->locations[$location->getName()] = $location;
    }

    /**
     * @param DirectiveLocation|non-empty-string $location
     */
    public function removeLocation(DirectiveLocation|string $location): void
    {
        if ($location instanceof DirectiveLocation) {
            $location = $location->getName();
        }

        unset($this->locations[$location]);
    }

    public function setIsRepeatable(bool $repeatable): void
    {
        $this->isRepeatable = $repeatable;
    }

    public function __toString(): string
    {
        return \sprintf('directive<@%s>', $this->getName());
    }
}
