<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Node\Statement\Definition\EnumTypeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\EnumTypeExtensionNode;
use Railt\TypeSystem\EnumTypeDefinition;

/**
 * @template-extends BuildCommand<EnumTypeDefinitionNode|EnumTypeExtensionNode, EnumTypeDefinition>
 */
final class BuildEnumTypeDefinitionCommand extends BuildCommand
{
    public function exec(): void
    {
        foreach ($this->node->fields as $node) {
            $this->ctx->push(new BuildEnumValueDefinitionCommand(
                ctx: $this->ctx,
                node: $node,
                definition: $this->definition,
            ));
        }

        foreach ($this->node->directives as $node) {
            $this->ctx->push(new EvaluateDirective(
                ctx: $this->ctx,
                node: $node,
                parent: $this->definition,
            ));
        }
    }
}
