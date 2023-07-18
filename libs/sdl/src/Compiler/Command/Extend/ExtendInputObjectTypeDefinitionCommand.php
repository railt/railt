<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Extend;

use Railt\SDL\Compiler\Command\Build\BuildInputObjectTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\ExtendCommand;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Extension\InputObjectTypeExtensionNode;
use Railt\TypeSystem\InputObjectTypeDefinition;

/**
 * @template-extends ExtendCommand<InputObjectTypeExtensionNode>
 */
final class ExtendInputObjectTypeDefinitionCommand extends ExtendCommand
{
    public function exec(): void
    {
        $type = $this->ctx->getType($this->stmt->name->value, $this->stmt->name);

        if (!$type instanceof InputObjectTypeDefinition) {
            $message = \vsprintf('Cannot extend %s by input object extension', [
                (string)$type,
            ]);

            throw CompilationException::create($message, $this->stmt->name);
        }

        $this->build(BuildInputObjectTypeDefinitionCommand::class, $type);
    }
}
