<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildSchemaDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\SchemaDefinitionNode;
use Railt\TypeSystem\Definition\SchemaDefinition;

/**
 * @template-extends DefineCommand<SchemaDefinitionNode>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class DefineSchemaCommand extends DefineCommand
{
    public function exec(): void
    {
        $schema = new SchemaDefinition();

        if ($this->stmt->description->value !== null) {
            $schema->setDescription($this->stmt->description->value->value);
        }

        $this->ctx->setSchema($schema, $this->stmt);

        $this->ctx->push(new BuildSchemaDefinitionCommand(
            ctx: $this->ctx,
            node: $this->stmt,
            definition: $schema,
        ));
    }
}
