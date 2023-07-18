<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Define;

use Railt\SDL\Compiler\Command\Build\BuildSchemaDefinitionCommand;
use Railt\SDL\Compiler\Command\DefineCommand;
use Railt\SDL\Node\Statement\Definition\SchemaDefinitionNode;
use Railt\TypeSystem\SchemaDefinition;

/**
 * @template-extends DefineCommand<SchemaDefinitionNode>
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
