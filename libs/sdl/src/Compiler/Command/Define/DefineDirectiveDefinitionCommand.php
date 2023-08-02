<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildDirectiveDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\DirectiveDefinitionNode;
use Railt\TypeSystem\Definition\DirectiveDefinition;

/**
 * @template-extends DefineCommand<DirectiveDefinitionNode>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
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

        $this->ctx->exec(new BuildDirectiveDefinitionCommand(
            ctx: $this->ctx,
            node: $this->stmt,
            definition: $directive,
        ));
    }
}
