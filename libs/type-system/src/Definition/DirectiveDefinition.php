<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition;

use Railt\TypeSystem\Common\HasDescriptionTrait;
use Railt\TypeSystem\Definition\Common\HasArgumentsInterface;
use Railt\TypeSystem\Definition\Common\HasArgumentsTrait;
use Railt\TypeSystem\DefinitionInterface;
use Railt\TypeSystem\NamedDefinition;

class DirectiveDefinition extends NamedDefinition implements
    HasArgumentsInterface
{
    use HasDescriptionTrait;
    use HasArgumentsTrait;

    /**
     * @var array<non-empty-string, DirectiveLocationInterface>
     */
    private array $locations = [];

    private bool $isRepeatable = false;

    /**
     * @param iterable<DirectiveLocationInterface> $locations
     */
    public function setLocations(iterable $locations): void
    {
        $this->locations = [];

        foreach ($locations as $location) {
            $this->addLocation($location);
        }
    }

    /**
     * @param iterable<DirectiveLocationInterface> $locations
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

    public function addLocation(DirectiveLocationInterface $location): void
    {
        $this->locations[$location->getName()] = $location;
    }

    public function withAddedLocation(DirectiveLocationInterface $location): self
    {
        $self = clone $this;
        $self->addLocation($location);

        return $self;
    }

    /**
     * @param DirectiveLocationInterface|non-empty-string $location
     */
    public function removeLocation(DirectiveLocationInterface|string $location): void
    {
        if ($location instanceof DirectiveLocation) {
            $location = $location->getName();
        }

        unset($this->locations[$location]);
    }

    /**
     * @param DirectiveLocationInterface|non-empty-string $location
     */
    public function withoutLocation(DirectiveLocationInterface|string $location): self
    {
        $self = clone $this;
        $self->removeLocation($location);

        return $self;
    }

    public function hasLocation(DirectiveLocationInterface|string $location): bool
    {
        if ($location instanceof DirectiveLocationInterface) {
            $location = $location->getName();
        }

        return isset($this->locations[$location]);
    }

    /**
     * @return iterable<DirectiveLocationInterface>
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

    public function isAvailableFor(DefinitionInterface $definition): bool
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
