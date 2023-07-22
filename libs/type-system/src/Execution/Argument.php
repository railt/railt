<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Execution;

use Railt\TypeSystem\Definition\ArgumentDefinition;
use Railt\TypeSystem\NamedExecution;

final class Argument extends NamedExecution
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
        $definition = $this->getDefinition();

        return \vsprintf('argument<%s: %s>', [
            $this->getName(),
            (string)$definition->getType(),
        ]);
    }
}
