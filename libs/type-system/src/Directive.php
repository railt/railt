<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class Directive implements NameAwareInterface
{
    /**
     * @var array<non-empty-string, Argument>
     */
    private array $arguments = [];

    public function getName(): string
    {
        return $this->directive->getName();
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

    public function removeArguments(): void
    {
        $this->arguments = [];
    }

    public function addArgument(Argument $argument): void
    {
        $this->arguments[$argument->getName()] = $argument;
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

    public function __toString(): string
    {
        return \sprintf('directive<@%s>', $this->getName());
    }
}
