<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Node\Statement\Definition\ObjectLikeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\ObjectLikeExtensionNode;
use Railt\TypeSystem\ObjectLikeTypeDefinition;

/**
 * @template TStatementNode of ObjectLikeDefinitionNode|ObjectLikeExtensionNode
 * @template TDefinition of ObjectLikeTypeDefinition
 *
 * @template-extends BuildCommand<TStatementNode, TDefinition>
 */
abstract class BuildObjectLikeTypeDefinitionCommand extends BuildCommand
{
    public function exec(): void
    {
        foreach ($this->node->fields as $node) {
            $this->ctx->push(new BuildFieldDefinitionCommand(
                ctx: $this->ctx,
                node: $node,
                definition: $this->definition,
            ));
        }

        $this->ctx->push(new ObjectLikeImplementsCommand(
            ctx: $this->ctx,
            node: $this->node,
            definition: $this->definition,
        ));

        foreach ($this->node->directives as $node) {
            $this->ctx->push(new EvaluateDirective(
                ctx: $this->ctx,
                node: $node,
                parent: $this->definition,
            ));
        }
    }
}
