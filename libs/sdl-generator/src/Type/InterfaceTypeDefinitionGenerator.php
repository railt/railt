<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Type;

use Railt\TypeSystem\Definition\Type\InterfaceType;

/**
 * @template-extends ObjectLikeTypeDefinitionGenerator<InterfaceType>
 */
final class InterfaceTypeDefinitionGenerator extends ObjectLikeTypeDefinitionGenerator
{
    protected function getTitle(): string
    {
        if ($this->type->getNumberOfInterfaces() > 0) {
            return \vsprintf('interface %s %s', [
                $this->type->getName(),
                $this->getInterfaces(),
            ]);
        }

        return \sprintf('interface %s', $this->type->getName());
    }
}
