<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Compiler\Command\Evaluate\InterfaceImplementsCommand;
use Railt\SDL\Node\Statement\Definition\ObjectLikeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\ObjectLikeExtensionNode;
use Railt\TypeSystem\Definition\Type\ObjectLikeType;

/**
 * @template TStatementNode of ObjectLikeDefinitionNode|ObjectLikeExtensionNode
 * @template TDefinition of ObjectLikeType
 *
 * @template-extends BuildCommand<TStatementNode, TDefinition>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
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

        $this->ctx->push(new InterfaceImplementsCommand(
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
