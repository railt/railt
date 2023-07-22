<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Type;

final class ObjectTypeDefinition extends ObjectLikeTypeDefinition
{
    public function __toString(): string
    {
        return \sprintf('object<%s>', $this->getName());
    }
}
