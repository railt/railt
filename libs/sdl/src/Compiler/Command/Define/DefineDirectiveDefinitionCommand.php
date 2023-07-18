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
        $type = new DirectiveDefinition($this->stmt->name->value);

        if ($this->stmt->description->value !== null) {
            $type->setDescription($this->stmt->description->value->value);
        }

        $this->ctx->addDirective($type, $this->stmt->name);

        $this->build(BuildDirectiveDefinitionCommand::class, $type);
    }
}
