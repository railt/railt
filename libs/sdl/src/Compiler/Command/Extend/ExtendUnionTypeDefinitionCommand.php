<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Extend;

use Railt\SDL\Compiler\Command\Build\BuildUnionTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\ExtendCommand;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Extension\UnionTypeExtensionNode;
use Railt\TypeSystem\UnionTypeDefinition;

/**
 * @template-extends ExtendCommand<UnionTypeExtensionNode>
 */
final class ExtendUnionTypeDefinitionCommand extends ExtendCommand
{
    public function exec(): void
    {
        $type = $this->ctx->getType($this->stmt->name->value, $this->stmt->name);

        if (!$type instanceof UnionTypeDefinition) {
            $message = \vsprintf('Cannot extend %s by union extension', [
                (string)$type,
            ]);

            throw CompilationException::create($message, $this->stmt->name);
        }

        $this->ctx->push(new BuildUnionTypeDefinitionCommand(
            ctx: $this->ctx,
            node: $this->stmt,
            definition: $type,
        ));
    }
}
