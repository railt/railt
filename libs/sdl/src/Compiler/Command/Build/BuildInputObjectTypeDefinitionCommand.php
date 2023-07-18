<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Node\Statement\Definition\InputObjectTypeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\InputObjectTypeExtensionNode;
use Railt\TypeSystem\InputObjectTypeDefinition;

/**
 * @template-extends BuildCommand<InputObjectTypeDefinitionNode|InputObjectTypeExtensionNode, InputObjectTypeDefinition>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class BuildInputObjectTypeDefinitionCommand extends BuildCommand
{
    public function exec(): void
    {
        foreach ($this->node->fields as $node) {
            $this->ctx->push(new BuildInputFieldDefinitionCommand(
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
