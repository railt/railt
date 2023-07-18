<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Extend;

use Railt\SDL\Compiler\Command\Build\BuildObjectTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\ExtendCommand;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Extension\ObjectTypeExtensionNode;
use Railt\TypeSystem\ObjectTypeDefinition;

/**
 * @template-extends ExtendCommand<ObjectTypeExtensionNode>
 */
final class ExtendObjectTypeDefinitionCommand extends ExtendCommand
{
    public function exec(): void
    {
        $type = $this->ctx->getType($this->stmt->name->value, $this->stmt->name);

        if (!$type instanceof ObjectTypeDefinition) {
            $message = \vsprintf('Cannot extend %s by object extension', [
                (string)$type,
            ]);

            throw CompilationException::create($message, $this->stmt->name);
        }

        $this->ctx->push(new BuildObjectTypeDefinitionCommand(
            ctx: $this->ctx,
            node: $this->stmt,
            definition: $type,
        ));
    }
}
