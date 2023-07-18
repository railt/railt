<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildChildCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateArgumentDefaultValue;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\ArgumentNode;
use Railt\TypeSystem\ArgumentDefinition;
use Railt\TypeSystem\DirectiveDefinition;
use Railt\TypeSystem\FieldDefinition;
use Railt\TypeSystem\InputTypeInterface;
use Railt\TypeSystem\TypeInterface;

/**
 * @template-extends BuildChildCommand<ArgumentNode, FieldDefinition|DirectiveDefinition>
 */
final class BuildArgumentDefinitionCommand extends BuildChildCommand
{
    public function exec(): void
    {
        $this->assertArgumentNotDefined();

        /** @var InputTypeInterface $type */
        $type = $this->getTypeReference($this->node->type, $this->definition);

        $this->assertTypeIsInput($type);

        $argument = new ArgumentDefinition(
            name: $this->node->name->value,
            type: $type,
        );

        if ($this->node->description->value !== null) {
            $argument->setDescription($this->node->description->value->value);
        }

        if ($this->node->default !== null) {
            $this->ctx->push(new EvaluateArgumentDefaultValue(
                ctx: $this->ctx,
                argument: $argument,
                node: $this->node,
            ));
        }

        foreach ($this->node->directives as $node) {
            $this->ctx->push(new EvaluateDirective(
                ctx: $this->ctx,
                node: $node,
                parent: $argument,
            ));
        }

        $this->definition->addArgument($argument);
    }

    /**
     * @param TypeInterface $type
     * @return ($type is InputTypeInterface ? void : never)
     */
    private function assertTypeIsInput(TypeInterface $type): void
    {
        if (!$this->isInputType($type)) {
            $message = \vsprintf('Argument "%s" must contain input type, but %s given', [
                $this->node->name->value,
                (string)$type,
            ]);

            throw CompilationException::create($message, $this->node->type);
        }
    }

    private function assertArgumentNotDefined(): void
    {
        if ($this->definition->hasArgument($this->node->name->value)) {
            $message = \vsprintf('Cannot redefine already defined argument "%s" in %s', [
                $this->node->name->value,
                (string)$this->definition,
            ]);

            throw CompilationException::create($message, $this->node);
        }
    }
}
