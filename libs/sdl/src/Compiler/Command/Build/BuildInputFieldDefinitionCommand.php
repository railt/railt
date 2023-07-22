<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildChildCommand;
use Railt\SDL\Compiler\Command\Evaluate\ApplyDeprecationFromTypeReferenceCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateInputFieldDefaultValue;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\InputFieldNode;
use Railt\TypeSystem\Definition\InputFieldDefinition;
use Railt\TypeSystem\Definition\Type\InputObjectType;
use Railt\TypeSystem\InputTypeInterface;
use Railt\TypeSystem\TypeInterface;

/**
 * @template-extends BuildChildCommand<InputFieldNode, InputObjectType>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class BuildInputFieldDefinitionCommand extends BuildChildCommand
{
    public function exec(): void
    {
        $this->assertInputFieldNotDefined();

        /** @var InputTypeInterface $type */
        $type = $this->getTypeReference($this->node->type, $this->definition);

        $this->assertTypeIsInput($type);

        $field = new InputFieldDefinition(
            name: $this->node->name->value,
            type: $type,
        );

        if ($this->node->description->value !== null) {
            $field->setDescription($this->node->description->value->value);
        }

        if ($this->node->default !== null) {
            $this->ctx->push(new EvaluateInputFieldDefaultValue(
                ctx: $this->ctx,
                field: $field,
                node: $this->node,
            ));
        }

        foreach ($this->node->directives as $node) {
            $this->ctx->push(new EvaluateDirective(
                ctx: $this->ctx,
                node: $node,
                parent: $field,
            ));
        }

        $this->definition->addField($field);

        // Resolve deprecation from reference
        $this->ctx->push(new ApplyDeprecationFromTypeReferenceCommand(
            context: $field,
        ));
    }

    private function assertInputFieldNotDefined(): void
    {
        if ($this->definition->hasField($this->node->name->value)) {
            $message = \vsprintf('Cannot redefine already defined input field "%s" in %s', [
                $this->node->name->value,
                (string)$this->definition,
            ]);

            throw CompilationException::create($message, $this->node);
        }
    }

    /**
     * @param TypeInterface $type
     * @return ($type is InputTypeInterface ? void : never)
     */
    private function assertTypeIsInput(TypeInterface $type): void
    {
        if (!$this->isInputType($type)) {
            $message = \vsprintf('Input field "%s" must contain input type, but %s given', [
                $this->node->name->value,
                (string)$type,
            ]);

            throw CompilationException::create($message, $this->node->type);
        }
    }
}
