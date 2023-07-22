<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Definition\SchemaDefinitionNode;
use Railt\SDL\Node\Statement\SchemaFieldNode;
use Railt\TypeSystem\Definition\SchemaDefinition;
use Railt\TypeSystem\Definition\Type\ObjectType;

/**
 * @template-extends BuildCommand<SchemaDefinitionNode, SchemaDefinition>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class BuildSchemaDefinitionCommand extends BuildCommand
{
    public function exec(): void
    {
        foreach ($this->node->fields as $node) {
            $type = $this->buildFieldType($node);

            switch ($node->name->value) {
                case 'query':
                    $this->buildQueryType($node, $type);
                    break;
                case 'mutation':
                    $this->buildMutationType($node, $type);
                    break;
                case 'subscription':
                    $this->buildSubscriptionType($node, $type);
                    break;
            }
        }

        foreach ($this->node->directives as $node) {
            $this->ctx->push(new EvaluateDirective(
                ctx: $this->ctx,
                node: $node,
                parent: $this->definition,
            ));
        }
    }

    private function buildFieldType(SchemaFieldNode $field): ObjectType
    {
        $type = $this->ctx->getType($field->type->name->value, $field->type, $this->definition);

        if (!$type instanceof ObjectType) {
            $message = \vsprintf('The %s schema field must be an object type, but %s given', [
                $field->name->value,
                (string)$type,
            ]);

            throw CompilationException::create($message, $field->type);
        }

        return $type;
    }

    private function buildSubscriptionType(SchemaFieldNode $field, ObjectType $type): void
    {
        if ($this->definition->getSubscriptionType() !== null) {
            $message = 'Cannot redefine already defined "subscription" field';
            throw CompilationException::create($message, $field->name);
        }

        $this->definition->setSubscriptionType($type);
    }

    private function buildMutationType(SchemaFieldNode $field, ObjectType $type): void
    {
        if ($this->definition->getMutationType() !== null) {
            $message = 'Cannot redefine already defined "mutation" field';
            throw CompilationException::create($message, $field->name);
        }

        $this->definition->setMutationType($type);
    }

    private function buildQueryType(SchemaFieldNode $field, ObjectType $type): void
    {
        if ($this->definition->getQueryType() !== null) {
            $message = 'Cannot redefine already defined "query" field';
            throw CompilationException::create($message, $field->name);
        }

        $this->definition->setQueryType($type);
    }
}
