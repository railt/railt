<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition;

use Railt\TypeSystem\Execution\Common\HasDirectivesTrait;
use Railt\TypeSystem\NamedDefinition;

abstract class NamedTypeDefinition extends NamedDefinition implements NamedTypeDefinitionInterface
{
    use HasDirectivesTrait;
}
