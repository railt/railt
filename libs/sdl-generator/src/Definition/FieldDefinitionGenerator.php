<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Definition;

use Railt\SDL\Generator\Type\DefinitionGenerator;
use Railt\TypeSystem\Definition\FieldDefinition;

/**
 * @template-extends DefinitionGenerator<FieldDefinition>
 */
final class FieldDefinitionGenerator extends DefinitionGenerator
{
    public function __toString(): string
    {
        $result = [];

        if ($description = $this->type->getDescription()) {
            $result[] = $this->description($description);
        }

        if ($this->type->getNumberOfArguments() > 0) {
            $result[] = \sprintf('%s(', $this->type->getName());

            foreach ($this->type->getArguments() as $argument) {
                $formatted = new ArgumentDefinitionGenerator($argument);

                $result[] = $this->printer->prefixed(1, (string)$formatted);
            }

            $result[] = \sprintf('): %s', $this->type($this->type->getType()));
        } else {
            $result[] = \vsprintf('%s: %s', [
                $this->type->getName(),
                $this->type($this->type->getType()),
            ]);
        }

        foreach ($this->type->getDirectives() as $directive) {
            $result[] = $this->directive($directive, 1);
        }

        return $this->printer->join($result);
    }
}
