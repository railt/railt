<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildEnumTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\EnumTypeDefinitionNode;
use Railt\TypeSystem\Definition\Type\EnumType;

/**
 * @template-extends DefineCommand<EnumTypeDefinitionNode>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class DefineEnumTypeDefinitionCommand extends DefineCommand
{
    public function exec(): void
    {
        $type = new EnumType($this->stmt->name->value);

        if ($this->stmt->description->value !== null) {
            $type->setDescription($this->stmt->description->value->value);
        }

        $this->ctx->addType($type, $this->stmt->name);

        $this->ctx->push(new BuildEnumTypeDefinitionCommand(
            ctx: $this->ctx,
            node: $this->stmt,
            definition: $type,
        ));
    }
}
