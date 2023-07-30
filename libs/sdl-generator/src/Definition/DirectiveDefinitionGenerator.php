<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Definition;

use Railt\SDL\Generator\Config;
use Railt\SDL\Generator\Type\DefinitionGenerator;
use Railt\TypeSystem\Definition\DirectiveDefinition;

/**
 * @template-extends DefinitionGenerator<DirectiveDefinition>
 */
final class DirectiveDefinitionGenerator extends DefinitionGenerator
{
    public function __construct(
        private readonly DirectiveDefinition $directive,
        Config $config = new Config(),
    ) {
        parent::__construct($config);
    }

    private function getRepeatable(): string
    {
        return $this->directive->isRepeatable() ? ' repeatable' : '';
    }

    public function __toString(): string
    {
        $result = [];

        if ($description = $this->directive->getDescription()) {
            $result[] = $this->description($description);
        }

        if ($this->directive->getNumberOfArguments()) {
            $result[] = \sprintf('directive @%s(', $this->directive->getName());

            foreach ($this->directive->getArguments() as $argument) {
                $formatted = new ArgumentDefinitionGenerator($argument);

                $result[] = $this->printer->prefixed(1, (string)$formatted);
            }

            $result[] = ')' . ($this->getRepeatable()) . ' on';
        } else {
            $result[] = \sprintf('directive @%s', $this->directive->getName())
                . ($this->getRepeatable()) . ' on';
        }

        foreach ($this->directive->getLocations() as $location) {
            $result[] = $this->printer->prefixed(1, '| %s', [
                $location->getName(),
            ]);
        }

        return $this->printer->join($result);
    }
}
