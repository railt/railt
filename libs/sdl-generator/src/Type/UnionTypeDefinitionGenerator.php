<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Type;

use Railt\TypeSystem\Definition\Type\UnionType;

/**
 * @template-extends TypeDefinitionGenerator<UnionType>
 */
final class UnionTypeDefinitionGenerator extends TypeDefinitionGenerator
{
    public function __toString(): string
    {
        $result = [];

        if ($description = $this->type->getDescription()) {
            $result[] = $this->description($description);
        }

        if ($this->type->getNumberOfDirectives()) {
            $result[] = \sprintf('union %s', $this->type->getName());

            foreach ($this->type->getDirectives() as $directive) {
                $result[] = $this->directive($directive, 1);
            }

            $result[] = '=';
        } else {
            $result[] = \sprintf('union %s =', $this->type->getName());
        }

        foreach ($this->type->getTypes() as $type) {
            $result[] = $this->printer->prefixed(1, '| %s', [
                $type->getName(),
            ]);
        }

        return $this->printer->join($result);
    }
}
