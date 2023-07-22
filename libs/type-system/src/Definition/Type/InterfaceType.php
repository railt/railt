<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Type;

final class InterfaceType extends ObjectLikeType
{
    public function __toString(): string
    {
        return \sprintf('interface<%s>', $this->getName());
    }
}
