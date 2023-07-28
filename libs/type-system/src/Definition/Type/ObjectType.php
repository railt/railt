<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Type;

class ObjectType extends ObjectLikeType
{
    public function __toString(): string
    {
        return \sprintf('object<%s>', $this->getName());
    }
}
