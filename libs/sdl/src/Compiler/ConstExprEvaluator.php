<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Compiler\Command\Evaluate\EvaluateInputObjectValue;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Expression\Expression;
use Railt\SDL\Node\Expression\Literal\BoolLiteralNode;
use Railt\SDL\Node\Expression\Literal\ConstLiteralNode;
use Railt\SDL\Node\Expression\Literal\FloatLiteralNode;
use Railt\SDL\Node\Expression\Literal\IntLiteralNode;
use Railt\SDL\Node\Expression\Literal\ListLiteralNode;
use Railt\SDL\Node\Expression\Literal\NullLiteralNode;
use Railt\SDL\Node\Expression\Literal\ObjectLiteralNode;
use Railt\SDL\Node\Expression\Literal\StringLiteralNode;
use Railt\TypeSystem\Definition\NamedTypeDefinition;
use Railt\TypeSystem\Definition\Type\EnumType;
use Railt\TypeSystem\Definition\Type\InputObjectType;
use Railt\TypeSystem\Definition\Type\ScalarType;
use Railt\TypeSystem\Execution\EnumValue;
use Railt\TypeSystem\Execution\InputObject;
use Railt\TypeSystem\ListType;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\TypeInterface;

final class ConstExprEvaluator
{
    public function __construct(
        private readonly Queue $queue,
    ) {}

    public function eval(TypeInterface $type, Expression $expr): mixed
    {
        return match (true) {
            $type instanceof ListType => $this->evalListType($type, $expr),
            $type instanceof NonNullType => $this->evalNonNullType($type, $expr),
            $type instanceof NamedTypeDefinition => $this->evalNamedType($type, $expr),
            default => throw CompilationException::create(
                'Cannot check expression compatibility of unknown type ' . (string)$type,
                $expr,
            ),
        };
    }

    private function evalNamedType(NamedTypeDefinition $type, Expression $expr): mixed
    {
        if ($expr instanceof NullLiteralNode) {
            return null;
        }

        return match (true) {
            $type instanceof ScalarType => $this->evalScalarType($type, $expr),
            $type instanceof InputObjectType => $this->evalInputType($type, $expr),
            $type instanceof EnumType => $this->evalEnumType($type, $expr),
            default => throw CompilationException::create(
                'Cannot check expression compatibility of unknown named type ' . (string)$type,
                $expr,
            ),
        };
    }

    private function evalEnumType(EnumType $enum, Expression $expr): EnumValue
    {
        if (!$expr instanceof ConstLiteralNode) {
            $message = \vsprintf('Cannot pass non-identifier literal to %s', [
                (string)$enum,
            ]);

            throw CompilationException::create($message, $expr);
        }

        $definition = $enum->getValue($expr->value->value);

        if ($definition === null) {
            $message = \vsprintf('Invalid enum value "%s" of %s', [
                $expr->value->value,
                (string)$enum,
            ]);

            throw CompilationException::create($message, $expr);
        }

        return new EnumValue($definition);
    }

    private function evalScalarType(ScalarType $type, Expression $expr): mixed
    {
        return match ($type->getName()) {
            'String', 'ID' => $this->evalStringScalarType($type, $expr),
            'Int' => $this->evalIntScalarType($type, $expr),
            'Float' => $this->evalFloatScalarType($type, $expr),
            'Boolean' => $this->evalBoolScalarType($type, $expr),
            default => throw CompilationException::create(
                'Cannot check expression compatibility of unknown scalar ' . (string)$type,
                $expr,
            ),
        };
    }

    private function evalBoolScalarType(ScalarType $type, Expression $expr): bool
    {
        if ($expr instanceof BoolLiteralNode) {
            return $expr->value;
        }

        throw CompilationException::create(
            'Cannot pass non-bool literal value to ' . (string)$type,
            $expr
        );
    }

    private function evalFloatScalarType(ScalarType $type, Expression $expr): float
    {
        if ($expr instanceof IntLiteralNode) {
            return (float)$expr->value;
        }

        if ($expr instanceof FloatLiteralNode) {
            return $expr->value;
        }

        throw CompilationException::create(
            'Cannot pass non-float literal value to ' . (string)$type,
            $expr
        );
    }

    private function evalIntScalarType(ScalarType $type, Expression $expr): int
    {
        if ($expr instanceof IntLiteralNode) {
            return $expr->value;
        }

        throw CompilationException::create(
            'Cannot pass non-int literal value to ' . (string)$type,
            $expr
        );
    }

    private function evalStringScalarType(ScalarType $type, Expression $expr): string
    {
        if ($expr instanceof StringLiteralNode) {
            return $expr->value;
        }

        throw CompilationException::create(
            'Cannot pass non-string literal value to ' . (string)$type,
            $expr
        );
    }

    private function evalInputType(InputObjectType $type, Expression $expr): InputObject
    {
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

            /** @psalm-suppress MixedAssignment : Okay */
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
                /** @psalm-suppress MixedAssignment : Okay */
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
