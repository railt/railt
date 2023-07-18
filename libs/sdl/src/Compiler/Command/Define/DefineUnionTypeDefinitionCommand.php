<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildUnionTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\UnionTypeDefinitionNode;
use Railt\TypeSystem\UnionTypeDefinition;

/**
 * @template-extends DefineCommand<UnionTypeDefinitionNode>
 */
final class DefineUnionTypeDefinitionCommand extends DefineCommand
{
    public function exec(): void
    {
        $type = new UnionTypeDefinition($this->stmt->name->value);

        if ($this->stmt->description->value !== null) {
            $type->setDescription($this->stmt->description->value->value);
        }

        $this->ctx->addType($type, $this->stmt->name);

        $this->build(BuildUnionTypeDefinitionCommand::class, $type);
    }
}
