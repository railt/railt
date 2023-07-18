<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildChildCommand;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Definition\ObjectLikeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\ObjectLikeExtensionNode;
use Railt\SDL\Node\Statement\Type\NamedTypeNode;
use Railt\TypeSystem\InterfaceTypeDefinition;
use Railt\TypeSystem\ObjectLikeTypeDefinition;
use Railt\TypeSystem\TypeInterface;

/**
 * @template-extends BuildChildCommand<ObjectLikeDefinitionNode|ObjectLikeExtensionNode, ObjectLikeTypeDefinition>
 */
final class ObjectLikeImplementsCommand extends BuildChildCommand
{
    public function exec(): void
    {
        foreach ($this->node->interfaces as $node) {
            /** @var InterfaceTypeDefinition $interface */
            $interface = $this->ctx->getType($node->name->value, $node->name);

            $this->assertTypeIsInterface($node, $interface);
            $this->assertInterfaceNotDefined($node, $interface);
            $this->assertTypeSelfReference($node, $interface);

            $this->definition->addInterface($interface);
        }
    }

    private function assertTypeSelfReference(NamedTypeNode $node, InterfaceTypeDefinition $interface): void
    {
        $isTypeSameOrSelfImplements = $this->node->name->value === $node->name->value
            || $interface->implements($this->node->name->value)
        ;

        if ($isTypeSameOrSelfImplements) {
            $message = \vsprintf('%s cannot implement itself', [
                (string)$this->definition,
            ]);

            throw CompilationException::create($message, $node->name);
        }
    }

    /**
     * @param TypeInterface $type
     * @return ($type is InterfaceTypeDefinition ? void : never)
     */
    private function assertTypeIsInterface(NamedTypeNode $node, TypeInterface $type): void
    {
        if (!$type instanceof InterfaceTypeDefinition) {
            $message = \vsprintf('%s can contain only interface types, but %s given', [
                (string)$this->definition,
                (string)$type,
            ]);

            throw CompilationException::create($message, $node->name);
        }
    }

    private function assertInterfaceNotDefined(NamedTypeNode $node, InterfaceTypeDefinition $interface): void
    {
        if ($this->definition->implements($node->name->value)) {
            $message = \vsprintf('Cannot implement already implemented type "%s" in %s', [
                $node->name->value,
                (string)$interface,
            ]);

            throw CompilationException::create($message, $this->node);
        }
    }
}
