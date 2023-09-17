<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Execution\DirectiveNode;
use Railt\TypeSystem\Definition;
use Railt\TypeSystem\Definition\ArgumentDefinition;
use Railt\TypeSystem\Definition\DirectiveDefinition;
use Railt\TypeSystem\Definition\EnumValueDefinition;
use Railt\TypeSystem\Definition\FieldDefinition;
use Railt\TypeSystem\Definition\InputFieldDefinition;
use Railt\TypeSystem\Definition\NamedTypeDefinition;
use Railt\TypeSystem\Definition\SchemaDefinition;
use Railt\TypeSystem\Definition\Type\ScalarType;
use Railt\TypeSystem\Execution\Directive;

/**
 * @link NamedTypeDefinition
 * @link FieldDefinition
 * @link EnumValueDefinition
 * @link InputFieldDefinition
 * @link ArgumentDefinition
 * @link SchemaDefinition
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class EvaluateDirective implements CommandInterface
{
    /**
     * @param ( NamedTypeDefinition
     *        | FieldDefinition
     *        | EnumValueDefinition
     *        | InputFieldDefinition
     *        | ArgumentDefinition
     *        | SchemaDefinition
     * ) $parent
     */
    public function __construct(
        private readonly Context $ctx,
        private readonly DirectiveNode $node,
        private readonly Definition $parent,
    ) {}

    public function exec(): void
    {
        $definition = $this->ctx->getDirective(
            name: $this->node->name->value,
            node: $this->node->name,
            from: $this->parent,
        );

        $this->assertValidLocation($definition);
        $this->assertRepetition($definition);

        $directive = new Directive($definition);

        foreach ($this->node->arguments as $node) {
            $this->ctx->push(new EvaluateArgumentCommand(
                ctx: $this->ctx,
                node: $node,
                directive: $directive,
            ));
        }

        $this->ctx->push(new CheckMissingDirectiveArgumentsCommand(
            ctx: $this->ctx,
            node: $this->node,
            directive: $directive,
        ));

        $this->parent->addDirective($directive);

        // Apply "@deprecated" directive
        $this->ctx->push(new ApplyDeprecationCommand(
            context: $this->parent,
        ));

        if ($this->parent instanceof ScalarType) {
            // Apply "@specifiedBy" directive
            $this->ctx->push(new ApplySpecifiedByCommand(
                scalar: $this->parent,
            ));
        }
    }

    private function assertRepetition(DirectiveDefinition $directive): void
    {
        if (!$directive->isRepeatable() && $this->parent->hasDirective($directive->getName())) {
            $message = \vsprintf('Directive "@%s" is not repeatable and cannot be applied twice', [
                $directive->getName(),
            ]);

            throw CompilationException::create($message, $this->node->name);
        }
    }

    private function assertValidLocation(DirectiveDefinition $directive): void
    {
        // Check directive location
        if (!$directive->isAvailableFor($this->parent)) {
            $message = \vsprintf('Directive "@%s" cannot be applied on %s', [
                $directive->getName(),
                (string)$this->parent,
            ]);

            throw CompilationException::create($message, $this->node);
        }
    }
}
