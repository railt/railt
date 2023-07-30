<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Definition;

use Railt\SDL\Generator\Config;
use Railt\SDL\Generator\Type\DefinitionGenerator;
use Railt\TypeSystem\Definition\ArgumentDefinition;

/**
 * @template-extends DefinitionGenerator<ArgumentDefinition>
 */
final class ArgumentDefinitionGenerator extends DefinitionGenerator
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
            $definition .= ' = ' . (string)$this->value($this->argument->getDefaultValue());
        }

        $result[] = $definition;

        foreach ($this->argument->getDirectives() as $directive) {
            $result[] = $this->directive($directive, 1);
        }

        return $this->printer->join($result);
    }
}
