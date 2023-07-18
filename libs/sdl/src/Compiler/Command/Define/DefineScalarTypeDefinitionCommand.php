<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildScalarTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\ScalarTypeDefinitionNode;
use Railt\TypeSystem\ScalarTypeDefinition;

/**
 * @template-extends DefineCommand<ScalarTypeDefinitionNode>
 */
final class DefineScalarTypeDefinitionCommand extends DefineCommand
{
    public function exec(): void
    {
        $type = new ScalarTypeDefinition($this->stmt->name->value);

        if ($this->stmt->description->value !== null) {
            $type->setDescription($this->stmt->description->value->value);
        }

        $this->ctx->addType($type, $this->stmt->name);

        $this->ctx->push(new BuildScalarTypeDefinitionCommand(
            ctx: $this->ctx,
            node: $this->stmt,
            definition: $type,
        ));
    }
}
