<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Execution;

use Railt\TypeSystem\Definition\ArgumentDefinition;
use Railt\TypeSystem\NamedExecution;

class Argument extends NamedExecution
{
    public function __construct(
        private readonly ArgumentDefinition $definition,
        private readonly mixed $value,
    ) {}

    public function getName(): string
    {
        return $this->definition->getName();
    }

    public function getDefinition(): ArgumentDefinition
    {
        return $this->definition;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->definition;
    }
}
