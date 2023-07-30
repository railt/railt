<?php

declare(strict_types=1);

namespace Railt\SDL\Generator;

use Railt\TypeSystem\Definition\FieldDefinition;

final class FieldDefinitionGenerator extends Generator
{
    public function __construct(
        private readonly FieldDefinition $field,
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

        if ($this->field->getNumberOfArguments() > 0) {
            $result[] = \sprintf('%s(', $this->field->getName());

            foreach ($this->field->getArguments() as $argument) {
                $formatted = new ArgumentDefinitionGenerator($argument);

                $result[] = $this->printer->prefixed(1, (string)$formatted);
            }

            $result[] = \sprintf('): %s', $this->type($this->field->getType()));
        } else {
            $result[] = \vsprintf('%s: %s', [
                $this->field->getName(),
                $this->type($this->field->getType()),
            ]);
        }

        foreach ($this->field->getDirectives() as $directive) {
            $result[] = $this->directive($directive, 1);
        }

        return $this->printer->join($result);
    }
}
