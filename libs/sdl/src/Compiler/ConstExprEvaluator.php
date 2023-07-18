<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Compiler\Command\Evaluate\EvaluateInputObjectValue;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Expression\Expression;
use Railt\SDL\Node\Expression\Literal\ListLiteralNode;
use Railt\SDL\Node\Expression\Literal\NullLiteralNode;
use Railt\SDL\Node\Expression\Literal\ObjectLiteralNode;
use Railt\SDL\Node\Expression\Literal\StringLiteralNode;
use Railt\TypeSystem\InputObject;
use Railt\TypeSystem\InputObjectTypeDefinition;
use Railt\TypeSystem\ListType;
use Railt\TypeSystem\NamedTypeDefinitionDefinition;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\ScalarTypeDefinition;
use Railt\TypeSystem\TypeInterface;

final readonly class ConstExprEvaluator
{
    public function __construct(
        private Queue $queue,
    ) {}

    public function eval(TypeInterface $type, Expression $expr): mixed
    {
        return match (true) {
            $type instanceof ListType => $this->evalListType($type, $expr),
            $type instanceof NonNullType => $this->evalNonNullType($type, $expr),
            $type instanceof NamedTypeDefinitionDefinition => $this->evalNamedType($type, $expr),
            default => throw CompilationException::create(
                'Cannot check expression compatibility of unknown type ' . (string)$type,
                $expr,
            ),
        };
    }

    private function evalNamedType(NamedTypeDefinitionDefinition $type, Expression $expr): mixed
    {
        return match (true) {
            $type instanceof ScalarTypeDefinition => $this->evalScalarType($type, $expr),
            $type instanceof InputObjectTypeDefinition => $this->evalInputType($type, $expr),
            default => throw CompilationException::create(
                'Cannot check expression compatibility of unknown named type ' . (string)$type,
                $expr,
            ),
        };
    }

    private function evalScalarType(ScalarTypeDefinition $type, Expression $expr): mixed
    {
        return match ($type->getName()) {
            'String' => $this->evalStringScalarType($type, $expr),
            default => throw CompilationException::create(
                'Cannot check expression compatibility of unknown scalar ' . (string)$type,
                $expr,
            ),
        };
    }

    private function evalStringScalarType(ScalarTypeDefinition $type, Expression $expr): string
    {
        if ($expr instanceof StringLiteralNode) {
            return $expr->value;
        }

        throw CompilationException::create(
            'Cannot pass non-string literal value to ' . (string)$type,
            $expr
        );
    }

    private function evalInputType(InputObjectTypeDefinition $type, Expression $expr): ?InputObject
    {
        if ($expr instanceof NullLiteralNode) {
            return null;
        }

        if (!$expr instanceof ObjectLiteralNode) {
            $message = \vsprintf('Cannot pass non-object literal value to input object type %s', [
                (string)$type,
            ]);

            throw CompilationException::create($message, $expr);
        }

        $result = [];

        foreach ($expr->fields as $node) {
            $field = $type->getField($node->key->value);

            if ($field === null) {
                $message = \vsprintf('Unknown input object field "%s" of %s', [
                    $node->key->value,
                    (string)$type,
                ]);

                throw CompilationException::create($message, $node->key);
            }

            $result[$node->key->value] = $this->eval($field->getType(), $node->value);
        }

        $this->queue->push(new EvaluateInputObjectValue(
            node: $expr,
            input: $type,
            defaults: $result,
        ));

        return new InputObject($type, $result);
    }

    private function evalNonNullType(NonNullType $type, Expression $expr): mixed
    {
        if ($expr instanceof NullLiteralNode) {
            $message = \vsprintf('Cannot pass null literal value to non-null type %s', [
                (string)$type,
            ]);

            throw CompilationException::create($message, $expr);
        }

        return $this->eval($type->getOfType(), $expr);
    }

    private function evalListType(ListType $type, Expression $expr): array
    {
        if ($expr instanceof ListLiteralNode) {
            $result = [];

            foreach ($expr->value as $literal) {
                $result[] = $this->eval($type->getOfType(), $literal);
            }

            return $result;
        }

        $message = \vsprintf('Passed value must be a type of %s, but non-list type given', [
            (string)$type,
        ]);

        throw CompilationException::create($message, $expr);
    }
}
