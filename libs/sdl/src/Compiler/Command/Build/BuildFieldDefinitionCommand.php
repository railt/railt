<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildChildCommand;
use Railt\SDL\Compiler\Command\Evaluate\ApplyDeprecationFromTypeReferenceCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\FieldNode;
use Railt\TypeSystem\Definition\FieldDefinition;
use Railt\TypeSystem\Definition\Type\ObjectLikeType;
use Railt\TypeSystem\OutputTypeInterface;
use Railt\TypeSystem\TypeInterface;

/**
 * @template-extends BuildChildCommand<FieldNode, ObjectLikeType>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class BuildFieldDefinitionCommand extends BuildChildCommand
{
    public function exec(): void
    {
        $this->assertFieldNotDefined();

        /** @var OutputTypeInterface $type */
        $type = $this->getTypeReference($this->node->type, $this->definition);

        $this->assertTypeIsOutput($type);

        $field = new FieldDefinition(
            name: $this->node->name->value,
            type: $type,
        );

        if ($this->node->description->value !== null) {
            $field->setDescription($this->node->description->value->value);
        }

        foreach ($this->node->arguments as $node) {
            $this->ctx->push(new BuildArgumentDefinitionCommand(
                ctx: $this->ctx,
                node: $node,
                definition: $field,
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

    /**
     * @param TypeInterface $type
     * @return ($type is OutputTypeInterface ? void : never)
     */
    private function assertTypeIsOutput(TypeInterface $type): void
    {
        if (!$this->isOutputType($type)) {
            $message = \vsprintf('Field "%s" must contain output type, but %s given', [
                $this->node->name->value,
                (string)$type,
            ]);

            throw CompilationException::create($message, $this->node->type);
        }
    }

    private function assertFieldNotDefined(): void
    {
        if ($this->definition->hasField($this->node->name->value)) {
            $message = \vsprintf('Cannot redefine already defined field "%s" in %s', [
                $this->node->name->value,
                (string)$this->definition,
            ]);

            throw CompilationException::create($message, $this->node);
        }
    }
}
