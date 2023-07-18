<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildInterfaceTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\InterfaceTypeDefinitionNode;
use Railt\TypeSystem\InterfaceTypeDefinition;

/**
 * @template-extends DefineCommand<InterfaceTypeDefinitionNode>
 */
final class DefineInterfaceTypeDefinitionCommand extends DefineCommand
{
    public function exec(): void
    {
        $type = new InterfaceTypeDefinition($this->stmt->name->value);

        if ($this->stmt->description->value !== null) {
            $type->setDescription($this->stmt->description->value->value);
        }

        $this->ctx->addType($type, $this->stmt->name);

        $this->ctx->push(new BuildInterfaceTypeDefinitionCommand(
            ctx: $this->ctx,
            node: $this->stmt,
            definition: $type,
        ));
    }
}
