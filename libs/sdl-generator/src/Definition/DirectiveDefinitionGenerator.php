<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Definition;

use Railt\SDL\Generator\Type\DefinitionGenerator;
use Railt\TypeSystem\Definition\DirectiveDefinition;

/**
 * @template-extends DefinitionGenerator<DirectiveDefinition>
 */
final class DirectiveDefinitionGenerator extends DefinitionGenerator
{
    private function getRepeatable(): string
    {
        return $this->type->isRepeatable() ? ' repeatable' : '';
    }

    public function __toString(): string
    {
        $result = [];

        if ($description = $this->type->getDescription()) {
            $result[] = $this->description($description);
        }

        if ($this->type->getNumberOfArguments()) {
            $result[] = \sprintf('directive @%s(', $this->type->getName());

            foreach ($this->type->getArguments() as $argument) {
                $formatted = new ArgumentDefinitionGenerator($argument);

                $result[] = $this->printer->prefixed(1, (string)$formatted);
            }

            $result[] = ')' . ($this->getRepeatable()) . ' on';
        } else {
            $result[] = \sprintf('directive @%s', $this->type->getName())
                . ($this->getRepeatable()) . ' on';
        }

        foreach ($this->type->getLocations() as $location) {
            $result[] = $this->printer->prefixed(1, '| %s', [
                $location->getName(),
            ]);
        }

        return $this->printer->join($result);
    }
}
