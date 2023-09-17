<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Execution;

use Railt\TypeSystem\Definition\EnumValueDefinition;
use Railt\TypeSystem\NamedExecution;

class EnumValue extends NamedExecution implements ExpressionInterface
{
    public function __construct(
        private readonly EnumValueDefinition $definition,
    ) {}

    public function getName(): string
    {
        return $this->definition->getName();
    }

    public function getValue(): mixed
    {
        return $this->definition->getValue();
    }

    public function getDefinition(): EnumValueDefinition
    {
        return $this->definition;
    }

    public function __toString(): string
    {
        return (string)$this->definition;
    }
}
