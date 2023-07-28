<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Execution;

use Railt\TypeSystem\Definition\DirectiveDefinition;
use Railt\TypeSystem\NamedExecution;

class Directive extends NamedExecution
{
    /**
     * @var non-empty-string|null
     */
    private ?string $hash = null;

    /**
     * @var array<non-empty-string, Argument>
     */
    private array $arguments = [];

    /**
     * @param iterable<Argument> $arguments
     */
    public function __construct(
        private readonly DirectiveDefinition $definition,
        iterable $arguments = [],
    ) {
        $this->setArguments($arguments);
    }

    /**
     * @return non-empty-string
     *
     * @throws \Exception
     */
    public function getHash(): string
    {
        try {
            return $this->hash ??= \hash('xxh3', \random_bytes(64));
        } catch (\ValueError) {
            return $this->hash = \hash('crc32', \random_bytes(64));
        }
    }

    public function getName(): string
    {
        return $this->definition->getName();
    }

    public function getDefinition(): DirectiveDefinition
    {
        return $this->definition;
    }

    /**
     * @param iterable<Argument> $arguments
     */
    public function setArguments(iterable $arguments): void
    {
        $this->arguments = [];

        foreach ($arguments as $argument) {
            $this->addArgument($argument);
        }
    }

    /**
     * @param iterable<Argument> $arguments
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

    public function addArgument(Argument $argument): void
    {
        $this->arguments[$argument->getName()] = $argument;
    }

    public function withAddedArgument(Argument $argument): self
    {
        $self = clone $this;
        $self->addArgument($argument);

        return $self;
    }

    /**
     * @param Argument|non-empty-string $argument
     */
    public function removeArgument(Argument|string $argument): void
    {
        if ($argument instanceof Argument) {
            $argument = $argument->getName();
        }

        unset($this->arguments[$argument]);
    }

    /**
     * @param Argument|non-empty-string $argument
     */
    public function withoutArgument(Argument|string $argument): self
    {
        $self = clone $this;
        $self->removeArgument($argument);

        return $self;
    }

    public function getArgument(string $name): ?Argument
    {
        return $this->arguments[$name] ?? null;
    }

    public function getValue(string $name, mixed $default = null): mixed
    {
        if (!isset($this->arguments[$name])) {
            return $default;
        }

        return $this->arguments[$name]->getValue();
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
     * @return iterable<Argument>
     */
    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    public function __toString(): string
    {
        return (string)$this->definition;
    }
}
