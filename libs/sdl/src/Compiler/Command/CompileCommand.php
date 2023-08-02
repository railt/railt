<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Compiler\Command\Define\DefineDirectiveDefinitionCommand;
use Railt\SDL\Compiler\Command\Define\DefineEnumTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Define\DefineInputObjectTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Define\DefineInterfaceTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Define\DefineObjectTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Define\DefineScalarTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Define\DefineSchemaCommand;
use Railt\SDL\Compiler\Command\Define\DefineUnionTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Extend\ExtendEnumTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Extend\ExtendInputObjectTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Extend\ExtendInterfaceTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Extend\ExtendObjectTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Extend\ExtendScalarTypeDefinitionCommand;
use Railt\SDL\Compiler\Command\Extend\ExtendUnionTypeDefinitionCommand;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Exception\InternalErrorException;
use Railt\SDL\Node\Node;
use Railt\SDL\Node\Statement\Definition\DirectiveDefinitionNode;
use Railt\SDL\Node\Statement\Definition\EnumTypeDefinitionNode;
use Railt\SDL\Node\Statement\Definition\InputObjectTypeDefinitionNode;
use Railt\SDL\Node\Statement\Definition\InterfaceTypeDefinitionNode;
use Railt\SDL\Node\Statement\Definition\ObjectTypeDefinitionNode;
use Railt\SDL\Node\Statement\Definition\ScalarTypeDefinitionNode;
use Railt\SDL\Node\Statement\Definition\SchemaDefinitionNode;
use Railt\SDL\Node\Statement\Definition\UnionTypeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\EnumTypeExtensionNode;
use Railt\SDL\Node\Statement\Extension\InputObjectTypeExtensionNode;
use Railt\SDL\Node\Statement\Extension\InterfaceTypeExtensionNode;
use Railt\SDL\Node\Statement\Extension\ObjectTypeExtensionNode;
use Railt\SDL\Node\Statement\Extension\ScalarTypeExtensionNode;
use Railt\SDL\Node\Statement\Extension\UnionTypeExtensionNode;
use Railt\SDL\Node\Statement\Statement;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler
 */
final class CompileCommand extends Command
{
    /**
     * @var array<class-string<Statement>, class-string<DefineCommand>>
     */
    private const AST_TO_COMMAND_MAP = [
        SchemaDefinitionNode::class => DefineSchemaCommand::class,
        DirectiveDefinitionNode::class => DefineDirectiveDefinitionCommand::class,
        EnumTypeDefinitionNode::class => DefineEnumTypeDefinitionCommand::class,
        EnumTypeExtensionNode::class => ExtendEnumTypeDefinitionCommand::class,
        InputObjectTypeDefinitionNode::class => DefineInputObjectTypeDefinitionCommand::class,
        InputObjectTypeExtensionNode::class => ExtendInputObjectTypeDefinitionCommand::class,
        InterfaceTypeDefinitionNode::class => DefineInterfaceTypeDefinitionCommand::class,
        InterfaceTypeExtensionNode::class => ExtendInterfaceTypeDefinitionCommand::class,
        ObjectTypeDefinitionNode::class => DefineObjectTypeDefinitionCommand::class,
        ObjectTypeExtensionNode::class => ExtendObjectTypeDefinitionCommand::class,
        ScalarTypeDefinitionNode::class => DefineScalarTypeDefinitionCommand::class,
        ScalarTypeExtensionNode::class => ExtendScalarTypeDefinitionCommand::class,
        UnionTypeDefinitionNode::class => DefineUnionTypeDefinitionCommand::class,
        UnionTypeExtensionNode::class => ExtendUnionTypeDefinitionCommand::class,
    ];

    /**
     * @var array<class-string<Node>, class-string<DefineCommand>>
     */
    private array $map = self::AST_TO_COMMAND_MAP;

    /**
     * @param iterable<Node> $nodes
     */
    public function __construct(
        Context $ctx,
        private readonly iterable $nodes,
    ) {
        parent::__construct($ctx);
    }

    public function exec(): void
    {
        foreach ($this->nodes as $node) {
            $this->ctx->exec($this->getCommand($node));
        }
    }

    /**
     * @return class-string<DefineCommand>
     */
    private function getCommandClass(Node $node): string
    {
        foreach (self::AST_TO_COMMAND_MAP as $class => $command) {
            if ($node instanceof $class) {
                return $command;
            }
        }

        throw InternalErrorException::fromUnprocessableNode($node);
    }

    private function getCommand(Node $node): CommandInterface
    {
        $command = $this->map[$node::class]
            ??= $this->getCommandClass($node)
        ;

        return new $command($this->ctx, $node);
    }
}
