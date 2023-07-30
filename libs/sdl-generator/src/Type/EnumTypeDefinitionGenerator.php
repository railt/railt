<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Type;

use Railt\TypeSystem\Definition\Type\EnumType;

/**
 * @template-extends TypeDefinitionGenerator<EnumType>
 */
final class EnumTypeDefinitionGenerator extends TypeDefinitionGenerator
{
    public function __toString(): string
    {
        $result = [];

        if ($description = $this->type->getDescription()) {
            $result[] = $this->description($description);
        }

        if ($this->type->getNumberOfDirectives()) {
            $result[] = \sprintf('enum %s', $this->type->getName());

            foreach ($this->type->getDirectives() as $directive) {
                $result[] = $this->directive($directive, 1);
            }

            $result[] = '{';
        } else {
            $result[] = \sprintf('enum %s {', $this->type->getName());
        }

        foreach ($this->type->getValues() as $value) {
            if ($description = $value->getDescription()) {
                $result[] = $this->description($description, 1);
            }

            $result[] = $this->printer->prefixed(1, $value->getName());

            foreach ($value->getDirectives() as $directive) {
                $result[] = $this->directive($directive, 2);
            }
        }

        $result[] = '}';

        return $this->printer->join($result);
    }
}
