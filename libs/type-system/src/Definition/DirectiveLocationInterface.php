<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition;

use Railt\TypeSystem\Common\HasNameInterface;
use Railt\TypeSystem\DefinitionInterface;

interface DirectiveLocationInterface extends HasNameInterface
{
    public function isAvailableFor(DefinitionInterface $definition): bool;
}
