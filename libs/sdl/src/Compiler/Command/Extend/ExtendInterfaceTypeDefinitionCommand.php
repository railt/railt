<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Extend;

use Railt\SDL\Compiler\Command\Build\BuildInterfaceTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\ExtendCommand;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Extension\InterfaceTypeExtensionNode;
use Railt\TypeSystem\InterfaceTypeDefinition;

/**
 * @template-extends ExtendCommand<InterfaceTypeExtensionNode>
 */
final class ExtendInterfaceTypeDefinitionCommand extends ExtendCommand
{
    public function exec(): void
    {
        $type = $this->ctx->getType($this->stmt->name->value, $this->stmt->name);

        if (!$type instanceof InterfaceTypeDefinition) {
            $message = \vsprintf('Cannot extend %s by interface extension', [
                (string)$type,
            ]);

            throw CompilationException::create($message, $this->stmt->name);
        }

        $this->build(BuildInterfaceTypeDefinitionCommand::class, $type);
    }
}
