<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildObjectTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\ObjectTypeDefinitionNode;
use Railt\TypeSystem\ObjectTypeDefinition;

/**
 * @template-extends DefineCommand<ObjectTypeDefinitionNode>
 */
final class DefineObjectTypeDefinitionCommand extends DefineCommand
{
    public function exec(): void
    {
        $type = new ObjectTypeDefinition($this->stmt->name->value);

        if ($this->stmt->description->value !== null) {
            $type->setDescription($this->stmt->description->value->value);
        }

        $this->ctx->addType($type, $this->stmt->name);

        $this->ctx->push(new BuildObjectTypeDefinitionCommand(
            ctx: $this->ctx,
            node: $this->stmt,
            definition: $type,
        ));
    }
}
