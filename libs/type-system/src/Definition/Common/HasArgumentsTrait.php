<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Common;

use Railt\TypeSystem\Definition\ArgumentDefinition;

/**
 * @mixin HasArgumentsInterface
 * @psalm-require-implements HasArgumentsInterface
 */
trait HasArgumentsTrait
{
    /**
     * @var array<non-empty-string, ArgumentDefinition>
     */
    private array $arguments = [];

    /**
     * @param iterable<ArgumentDefinition> $arguments
     */
    public function setArguments(iterable $arguments): void
    {
        $this->arguments = [];

        foreach ($arguments as $argument) {
            $this->addArgument($argument);
        }
    }

    /**
     * @param iterable<ArgumentDefinition> $arguments
     */
    public function withArguments(iterable $arguments): self
    {
        $self = clone $this;
        $self->setArguments($arguments);

        return $self;
    }

    public function removeArguments(): void
    {
        $this->arguments = [];
    }

    public function withoutArguments(): self
    {
        $self = clone $this;
        $self->removeArguments();

        return $self;
    }

    public function addArgument(ArgumentDefinition $argument): void
    {
        $this->arguments[$argument->getName()] = $argument;
    }

    public function withAddedArgument(ArgumentDefinition $argument): self
    {
        $self = clone $this;
        $self->addArgument($argument);

        return $self;
    }

    /**
     * @param ArgumentDefinition|non-empty-string $argument
     */
    public function removeArgument(ArgumentDefinition|string $argument): void
    {
        if ($argument instanceof ArgumentDefinition) {
            $argument = $argument->getName();
        }

        unset($this->arguments[$argument]);
    }

    /**
     * @param ArgumentDefinition|non-empty-string $argument
     */
    public function withoutArgument(ArgumentDefinition|string $argument): self
    {
        $self = clone $this;
        $self->removeArgument($argument);

        return $self;
    }

    public function getArgument(string $name): ?ArgumentDefinition
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @return int<0, max>
     */
    public function getNumberOfArguments(): int
    {
        /** @var int<0, max> */
        return \count($this->arguments);
    }

    public function hasArgument(string $name): bool
    {
        return isset($this->arguments[$name]);
    }

    /**
     * @return list<ArgumentDefinition>
     */
    public function getArguments(): array
    {
        return \array_values($this->arguments);
    }
}
