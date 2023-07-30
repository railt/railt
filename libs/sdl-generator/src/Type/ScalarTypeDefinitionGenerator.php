<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Type;

use Railt\TypeSystem\Definition\Type\ScalarType;

/**
 * @template-extends TypeDefinitionGenerator<ScalarType>
 */
final class ScalarTypeDefinitionGenerator extends TypeDefinitionGenerator
{
    public function __toString(): string
    {
        $result = [];

        if ($description = $this->type->getDescription()) {
            $result[] = $this->description($description);
        }

        $result[] = \sprintf('scalar %s', $this->type->getName());

        foreach ($this->type->getDirectives() as $directive) {
            $result[] = $this->directive($directive, 1);
        }

        return $this->printer->join($result);
    }
}
