<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Type;

use Railt\SDL\Generator\Definition\InputFieldDefinitionGenerator;
use Railt\TypeSystem\Definition\Type\InputObjectType;

/**
 * @template-extends TypeDefinitionGenerator<InputObjectType>
 */
final class InputObjectTypeDefinitionGenerator extends TypeDefinitionGenerator
{
    public function __toString(): string
    {
        $result = [];

        if ($description = $this->type->getDescription()) {
            $result[] = $this->description($description);
        }

        if ($this->type->getNumberOfDirectives()) {
            $result[] = \sprintf('input %s', $this->type->getName());

            foreach ($this->type->getDirectives() as $directive) {
                $result[] = $this->directive($directive, 1);
            }

            $result[] = '{';
        } else {
            $result[] = \sprintf('input %s {', $this->type->getName());
        }

        foreach ($this->type->getFields() as $field) {
            $formatted = new InputFieldDefinitionGenerator($field, $this->config);

            $result[] = $this->printer->prefixed(1, (string)$formatted);
        }

        $result[] = '}';

        return $this->printer->join($result);
    }
}
