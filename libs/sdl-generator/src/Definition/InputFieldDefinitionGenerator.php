<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Definition;

use Railt\SDL\Generator\Config;
use Railt\SDL\Generator\Type\DefinitionGenerator;
use Railt\TypeSystem\Definition\InputFieldDefinition;

/**
 * @template-extends DefinitionGenerator<InputFieldDefinition>
 */
final class InputFieldDefinitionGenerator extends DefinitionGenerator
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
            $definition .= ' = ' . (string)$this->value($this->field->getDefaultValue());
        }

        $result[] = $definition;

        foreach ($this->field->getDirectives() as $directive) {
            $result[] = $this->directive($directive, 1);
        }

        return $this->printer->join($result);
    }
}
