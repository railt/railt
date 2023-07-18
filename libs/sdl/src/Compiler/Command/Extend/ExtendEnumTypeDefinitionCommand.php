<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Extend;

use Railt\SDL\Compiler\Command\Build\BuildEnumTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\ExtendCommand;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Extension\EnumTypeExtensionNode;
use Railt\TypeSystem\EnumTypeDefinition;

/**
 * @template-extends ExtendCommand<EnumTypeExtensionNode>
 */
final class ExtendEnumTypeDefinitionCommand extends ExtendCommand
{
    public function exec(): void
    {
        $type = $this->ctx->getType($this->stmt->name->value, $this->stmt->name);

        if (!$type instanceof EnumTypeDefinition) {
            $message = \vsprintf('Cannot extend %s by enum extension', [
                (string)$type,
            ]);

            throw CompilationException::create($message, $this->stmt->name);
        }

        $this->build(BuildEnumTypeDefinitionCommand::class, $type);
    }
}
