<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class InterfaceTypeDefinition extends ObjectLikeTypeDefinition
{
    public function __toString(): string
    {
        return \sprintf('interface<%s>', $this->getName());
    }
}
