<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class Argument extends Expression implements NameAwareInterface
{
    public function __construct(
        private readonly ArgumentDefinition $argument,
        private readonly mixed $value,
    ) {
    }

    public function getName(): string
    {
        return $this->argument->getName();
    }

    public function getDefinition(): ArgumentDefinition
    {
        return $this->argument;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return \sprintf('argument<%s>', $this->getName());
    }
}
