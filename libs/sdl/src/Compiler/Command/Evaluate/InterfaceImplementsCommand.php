<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Definition\ObjectLikeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\ObjectLikeExtensionNode;
use Railt\SDL\Node\Statement\Type\NamedTypeNode;
use Railt\TypeSystem\Definition\Type\InterfaceType;
use Railt\TypeSystem\Definition\Type\ObjectLikeType;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class InterfaceImplementsCommand implements CommandInterface
{
    public function __construct(
        private readonly Context $ctx,
        private readonly ObjectLikeDefinitionNode|ObjectLikeExtensionNode $node,
        private readonly ObjectLikeType $definition,
    ) {}

    public function exec(): void
    {
        foreach ($this->node->interfaces as $node) {
            $interface = $this->ctx->getType($node->name->value, $node->name);

            //
            // Check that a type is an interface
            //
            if (!$interface instanceof InterfaceType) {
                $message = \vsprintf('%s can contain only interface types, but %s given', [
                    (string)$this->definition,
                    (string)$interface,
                ]);

                throw CompilationException::create($message, $node->name);
            }

            $this->assertInterfaceNotDefined($node, $interface);
            $this->assertTypeSelfReference($node, $interface);

            $this->definition->addInterface($interface);
        }
    }

    private function assertTypeSelfReference(NamedTypeNode $node, InterfaceType $interface): void
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

    private function assertInterfaceNotDefined(NamedTypeNode $node, InterfaceType $interface): void
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
