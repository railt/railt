<?php

declare(strict_types=1);

namespace Railt\SDL\Generator;

use Railt\SDL\Generator\Value\ValueGeneratorFactory;
use Railt\TypeSystem\Definition\ArgumentDefinition;

final class ArgumentDefinitionGenerator extends Generator
{
    public function __construct(
        private readonly ArgumentDefinition $argument,
        Config $config = new Config(),
    ) {
        parent::__construct($config);
    }

    public function __toString(): string
    {
        $result = [];

        if ($description = $this->argument->getDescription()) {
            $result[] = $this->description($description);
        }

        $definition = \vsprintf('%s: %s', [
            $this->argument->getName(),
            $this->type($this->argument->getType()),
        ]);

        if ($this->argument->hasDefaultValue()) {
            $value = new ValueGeneratorFactory($this->argument->getDefaultValue(), $this->config);

            $definition .= ' = ' . (string)$value;
        }

        $result[] = $definition;

        foreach ($this->argument->getDirectives() as $directive) {
            $result[] = $this->directive($directive, 1);
        }

        return $this->printer->join($result);
    }
}
