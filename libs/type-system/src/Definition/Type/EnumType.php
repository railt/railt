<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Type;

use Railt\TypeSystem\Definition\Common\HasEnumValuesInterface;
use Railt\TypeSystem\Definition\Common\HasEnumValuesTrait;
use Railt\TypeSystem\Definition\NamedTypeDefinition;
use Railt\TypeSystem\InputTypeInterface;
use Railt\TypeSystem\OutputTypeInterface;

class EnumType extends NamedTypeDefinition implements
    InputTypeInterface,
    OutputTypeInterface,
    HasEnumValuesInterface
{
    use HasEnumValuesTrait;

    public function __toString(): string
    {
        return \sprintf('enum<%s>', $this->getName());
    }
}
