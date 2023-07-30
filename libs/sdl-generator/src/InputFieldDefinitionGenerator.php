<?php

declare(strict_types=1);

namespace Railt\SDL\Generator;

use Railt\SDL\Generator\Value\ValueGeneratorFactory;
use Railt\TypeSystem\Definition\InputFieldDefinition;

final class InputFieldDefinitionGenerator extends Generator
{
    public function __construct(
        private readonly InputFieldDefinition $field,
        Config $config = new Config(),
    ) {
        parent::__construct($config);
    }

    public function __toString(): string
    {
        $result = [];

        if ($description = $this->field->getDescription()) {
            $result[] = $this->description($description);
        }

        $definition = \vsprintf('%s: %s', [
            $this->field->getName(),
            $this->type($this->field->getType()),
        ]);

        if ($this->field->hasDefaultValue()) {
            $value = new ValueGeneratorFactory($this->field->getDefaultValue(), $this->config);

            $definition .= ' = ' . (string)$value;
        }

        $result[] = $definition;

        foreach ($this->field->getDirectives() as $directive) {
            $result[] = $this->directive($directive, 1);
        }

        return $this->printer->join($result);
    }
}
