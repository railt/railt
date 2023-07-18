<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildEnumTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\EnumTypeDefinitionNode;
use Railt\TypeSystem\EnumTypeDefinition;

/**
 * @template-extends DefineCommand<EnumTypeDefinitionNode>
 */
final class DefineEnumTypeDefinitionCommand extends DefineCommand
{
    public function exec(): void
    {
        $type = new EnumTypeDefinition($this->stmt->name->value);

        if ($this->stmt->description->value !== null) {
            $type->setDescription($this->stmt->description->value->value);
        }

        $this->ctx->addType($type, $this->stmt->name);

        $this->build(BuildEnumTypeDefinitionCommand::class, $type);
    }
}
