<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Node\Statement\Definition\ScalarTypeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\ScalarTypeExtensionNode;
use Railt\TypeSystem\Definition\NamedTypeDefinition;
use Railt\TypeSystem\Definition\Type\ScalarType;

/**
 * @template-extends BuildCommand<ScalarTypeDefinitionNode|ScalarTypeExtensionNode, ScalarType>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class BuildScalarTypeDefinitionCommand extends BuildCommand
{
    public function exec(): void
    {
        foreach ($this->node->directives as $node) {
            $this->ctx->push(new EvaluateDirective(
                ctx: $this->ctx,
                node: $node,
                parent: $this->definition,
            ));
        }
    }
}
