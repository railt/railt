<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Execution;

use Railt\TypeSystem\DefinitionInterface;

interface ExpressionInterface
{
    public function getDefinition(): DefinitionInterface;
}
