<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildInputObjectTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\InputObjectTypeDefinitionNode;
use Railt\TypeSystem\InputObjectTypeDefinition;

/**
 * @template-extends DefineCommand<InputObjectTypeDefinitionNode>
 */
final class DefineInputObjectTypeDefinitionCommand extends DefineCommand
{
    public function exec(): void
    {
        $type = new InputObjectTypeDefinition($this->stmt->name->value);

        if ($this->stmt->description->value !== null) {
            $type->setDescription($this->stmt->description->value->value);
        }

        $this->ctx->addType($type, $this->stmt->name);

        $this->build(BuildInputObjectTypeDefinitionCommand::class, $type);
    }
}
