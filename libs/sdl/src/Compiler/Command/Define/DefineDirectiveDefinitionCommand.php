<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildDirectiveDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\DirectiveDefinitionNode;
use Railt\TypeSystem\DirectiveDefinition;

/**
 * @template-extends DefineCommand<DirectiveDefinitionNode>
 */
final class DefineDirectiveDefinitionCommand extends DefineCommand
{
    public function exec(): void
    {
        $directive = new DirectiveDefinition($this->stmt->name->value);

        if ($this->stmt->description->value !== null) {
            $directive->setDescription($this->stmt->description->value->value);
        }

        $this->ctx->addDirective($directive, $this->stmt->name);

        $this->ctx->push(new BuildDirectiveDefinitionCommand(
            ctx: $this->ctx,
            node: $this->stmt,
            definition: $directive,
        ));
    }
}
