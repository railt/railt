<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Definition;

use Railt\SDL\Generator\Type\DefinitionGenerator;
use Railt\TypeSystem\Definition\ArgumentDefinition;

/**
 * @template-extends DefinitionGenerator<ArgumentDefinition>
 */
final class ArgumentDefinitionGenerator extends DefinitionGenerator
{
    public function __toString(): string
    {
        $result = [];

        if ($description = $this->type->getDescription()) {
            $result[] = $this->description($description);
        }

        $definition = \vsprintf('%s: %s', [
            $this->type->getName(),
            $this->type($this->type->getType()),
        ]);

        if ($this->type->hasDefaultValue()) {
            $definition .= ' = ' . (string)$this->value($this->type->getDefaultValue());
        }

        $result[] = $definition;

        foreach ($this->type->getDirectives() as $directive) {
            $result[] = $this->directive($directive, 1);
        }

        return $this->printer->join($result);
    }
}
