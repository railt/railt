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
     * @param non-empty-string $name
     */
    public function __construct(
        private readonly string $name,
    ) {
    }

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

    /**
     * @param iterable<DirectiveLocation> $locations
     */
    public function withLocations(iterable $locations): self
    {
        $self = clone $this;
        $self->setLocations($locations);

        return $self;
    }

    public function removeLocations(): void
    {
        $this->locations = [];
    }

    public function withoutLocations(): self
    {
        $self = clone $this;
        $self->removeLocations();

        return $self;
    }

    public function addLocation(DirectiveLocation $location): void
    {
        $this->locations[$location->getName()] = $location;
    }

    public function withAddedLocation(DirectiveLocation $location): self
    {
        $self = clone $this;
        $self->addLocation($location);

        return $self;
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

    /**
     * @param DirectiveLocation|non-empty-string $location
     */
    public function withoutLocation(DirectiveLocation|string $location): self
    {
        $self = clone $this;
        $self->removeLocation($location);

        return $self;
    }

    public function hasLocation(DirectiveLocation|string $location): bool
    {
        if ($location instanceof DirectiveLocation) {
            $location = $location->getName();
        }

        return isset($this->locations[$location]);
    }

    /**
     * @return iterable<DirectiveLocation>
     */
    public function getLocations(): iterable
    {
        return \array_values($this->locations);
    }

    /**
     * @return int<0, max>
     */
    public function getNumberOfLocations(): int
    {
        /** @var int<0, max> */
        return \count($this->locations);
    }

    public function isAvailableFor(Definition $definition): bool
    {
        foreach ($this->locations as $location) {
            if ($location->isAvailableFor($definition)) {
                return true;
            }
        }

        return false;
    }

    public function setIsRepeatable(bool $repeatable): void
    {
        $this->isRepeatable = $repeatable;
    }

    public function withIsRepeatable(bool $repeatable): self
    {
        $self = clone $this;
        $self->setIsRepeatable($repeatable);

        return $self;
    }

    public function isRepeatable(): bool
    {
        return $this->isRepeatable;
    }

    public function __toString(): string
    {
        return \sprintf('directive<@%s>', $this->getName());
    }
}
