<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Type;

use Railt\TypeSystem\Definition\Type\ObjectType;

/**
 * @template-extends ObjectLikeTypeDefinitionGenerator<ObjectType>
 */
final class ObjectTypeDefinitionGenerator extends ObjectLikeTypeDefinitionGenerator
{
    protected function getTitle(): string
    {
        if ($this->type->getNumberOfInterfaces() > 0) {
            return \vsprintf('type %s %s', [
                $this->type->getName(),
                $this->getInterfaces(),
            ]);
        }

        return \sprintf('type %s', $this->type->getName());
    }
}
