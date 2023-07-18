<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildCommand;
use Railt\SDL\Node\Statement\Definition\ScalarTypeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\ScalarTypeExtensionNode;
use Railt\TypeSystem\ScalarTypeDefinition;

/**
 * @template-extends BuildCommand<ScalarTypeDefinitionNode|ScalarTypeExtensionNode, ScalarTypeDefinition>
 */
final class BuildScalarTypeDefinitionCommand extends BuildCommand
{
    public function exec(): void
    {
        foreach ($this->node->directives as $node) {
            $this->addDirective($this->definition, $node);
        }
    }
}
