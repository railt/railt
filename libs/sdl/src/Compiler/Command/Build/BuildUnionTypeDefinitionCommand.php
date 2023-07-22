<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Definition\UnionTypeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\UnionTypeExtensionNode;
use Railt\SDL\Node\Statement\Type\NamedTypeNode;
use Railt\TypeSystem\Definition\Type\ObjectType;
use Railt\TypeSystem\Definition\Type\UnionType;
use Railt\TypeSystem\TypeInterface;

/**
 * @template-extends BuildCommand<UnionTypeDefinitionNode|UnionTypeExtensionNode, UnionType>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class BuildUnionTypeDefinitionCommand extends BuildCommand
{
    public function exec(): void
    {
        foreach ($this->node->types as $node) {
            $type = $this->ctx->getType($node->name->value, $node->name, $this->definition);

            $this->assertTypeIsObject($node, $type);
            $this->assertTypeNotDefined($node, $type);

            $this->definition->addType($type);
        }

        foreach ($this->node->directives as $node) {
            $this->ctx->push(new EvaluateDirective(
                ctx: $this->ctx,
                node: $node,
                parent: $this->definition,
            ));
        }
    }

    /**
     * @param TypeInterface $type
     * @return ($type is ObjectType ? void : never)
     */
    private function assertTypeIsObject(NamedTypeNode $node, TypeInterface $type): void
    {
        if (!$type instanceof ObjectType) {
            $message = \vsprintf('%s can contain only object types, but %s given', [
                (string)$this->definition,
                (string)$type,
            ]);

            throw CompilationException::create($message, $node->name);
        }
    }

    private function assertTypeNotDefined(NamedTypeNode $node, TypeInterface $type): void
    {
        if ($this->definition->hasType($node->name->value)) {
            $message = \vsprintf('Cannot redefine already defined type %s in %s', [
                (string)$type,
                (string)$this->definition,
            ]);

            throw CompilationException::create($message, $node->name);
        }
    }
}
