<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition;

use Railt\TypeSystem\Execution\Common\HasDirectivesInterface;
use Railt\TypeSystem\NamedDefinitionInterface;

interface NamedTypeDefinitionInterface extends
    HasDirectivesInterface,
    TypeDefinitionInterface,
    NamedDefinitionInterface {}
