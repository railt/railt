<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

abstract class Definition implements DefinitionInterface
{
    public function jsonSerialize(): array
    {
        /** @var array<non-empty-string, mixed> */
        return \get_object_vars($this);
    }
}
