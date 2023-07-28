<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Type;

use Railt\TypeSystem\Definition\Common\HasInputFieldsInterface;
use Railt\TypeSystem\Definition\Common\HasInputFieldsTrait;
use Railt\TypeSystem\Definition\NamedTypeDefinition;
use Railt\TypeSystem\InputTypeInterface;

class InputObjectType extends NamedTypeDefinition implements
    InputTypeInterface,
    HasInputFieldsInterface
{
    use HasInputFieldsTrait;

    public function __toString(): string
    {
        return \sprintf('input<%s>', $this->getName());
    }
}
