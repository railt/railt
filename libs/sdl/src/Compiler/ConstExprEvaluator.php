<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Compiler\Command\Evaluate\EvaluateInputObjectValue;
use Railt\SDL\Config;
use Railt\SDL\Exception\ExpressionException;
use Railt\SDL\Node\Expression\Expression;
use Railt\SDL\Node\Expression\Literal\BoolLiteralNode;
use Railt\SDL\Node\Expression\Literal\ConstLiteralNode;
use Railt\SDL\Node\Expression\Literal\FloatLiteralNode;
use Railt\SDL\Node\Expression\Literal\IntLiteralNode;
use Railt\SDL\Node\Expression\Literal\ListLiteralNode;
use Railt\SDL\Node\Expression\Literal\NullLiteralNode;
use Railt\SDL\Node\Expression\Literal\ObjectLiteralNode;
use Railt\SDL\Node\Expression\Literal\StringLiteralNode;
use Railt\SDL\Node\Expression\VariableNode;
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
    /**
     * @param array<non-empty-string, mixed> $variables
     */
    public function __construct(
        private readonly Queue $queue,
        private readonly array $variables,
        private readonly Config $config,
    ) {}

    public function eval(TypeInterface $type, Expression $expr): mixed
    {
        return match (true) {
            $type instanceof ListType => $this->evalListType($type, $expr),
            $type instanceof NonNullType => $this->evalNonNullType($type, $expr),
            $type instanceof NamedTypeDefinition => $this->evalNamedType($type, $expr),
            default => throw ExpressionException::fromUnprocessableExpr($type, $expr),
        };
    }

    public function evalWithValue(TypeInterface $type, Expression $ctx, mixed $value): mixed
    {
        return match (true) {
            $type instanceof ListType => $this->evalListTypeWithValue($type, $ctx, $value),
            $type instanceof NonNullType => $this->evalNonNullTypeWithValue($type, $ctx, $value),
            $type instanceof NamedTypeDefinition => $this->evalNamedTypeWithValue($type, $ctx, $value),
            default => throw ExpressionException::fromUnprocessableExprWithValue($type, $ctx, $value),
        };
    }

    public function fetchVariable(VariableNode $expr): mixed
    {
        if (\array_key_exists($expr->name, $this->variables)) {
            return $this->variables[$expr->name];
        }

        throw ExpressionException::fromUndefinedVariable($expr);
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
            default => throw ExpressionException::fromUnprocessableExpr($type, $expr),
        };
    }

    private function evalNamedTypeWithValue(NamedTypeDefinition $type, Expression $ctx, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return match (true) {
            $type instanceof ScalarType => $this->evalScalarTypeWithValue($type, $ctx, $value),
            $type instanceof InputObjectType => $this->evalInputTypeWithValue($type, $ctx, $value),
            $type instanceof EnumType => $this->evalEnumTypeWithValue($type, $ctx, $value),
            default => throw ExpressionException::fromUnprocessableExprWithValue($type, $ctx, $value),
        };
    }

    private function evalEnumType(EnumType $enum, Expression $expr): EnumValue
    {
        /** @psalm-suppress MixedAssignment */
        if ($expr instanceof VariableNode) {
            $value = $this->fetchVariable($expr);

            return $this->evalEnumTypeWithValue($enum, $expr, $value);
        }

        if (!$expr instanceof ConstLiteralNode) {
            throw ExpressionException::fromInvalidEnumValueType($enum, $expr);
        }

        $definition = $enum->getValue($expr->value->value);

        if ($definition === null) {
            throw ExpressionException::fromInvalidEnumValue($enum, $expr);
        }

        return new EnumValue($definition);
    }

    private function evalEnumTypeWithValue(EnumType $enum, Expression $expr, mixed $value): EnumValue
    {
        if (!\is_string($value) && !$value instanceof \Stringable) {
            throw ExpressionException::fromInvalidEnumValueTypeWithValue($enum, $expr, $value);
        }

        $definition = $enum->getValue((string)$value);

        if ($definition === null) {
            throw ExpressionException::fromInvalidEnumValueWithValue($enum, $expr, $value);
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
            default => throw ExpressionException::fromUnprocessableExpr($type, $expr),
        };
    }

    private function evalScalarTypeWithValue(ScalarType $type, Expression $ctx, mixed $value): mixed
    {
        return match ($type->getName()) {
            'String', 'ID' => $this->evalStringScalarTypeWithValue($type, $ctx, $value),
            'Int' => $this->evalIntScalarTypeWithValue($type, $ctx, $value),
            'Float' => $this->evalFloatScalarTypeWithValue($type, $ctx, $value),
            'Boolean' => $this->evalBoolScalarTypeWithValue($type, $ctx, $value),
            default => throw ExpressionException::fromUnprocessableExprWithValue($type, $ctx, $value),
        };
    }

    private function evalBoolScalarType(ScalarType $type, Expression $expr): bool
    {
        if ($expr instanceof BoolLiteralNode) {
            return $expr->value;
        }

        /** @psalm-suppress MixedAssignment */
        if ($expr instanceof VariableNode) {
            $value = $this->fetchVariable($expr);

            return $this->evalBoolScalarTypeWithValue($type, $expr, $value);
        }

        throw ExpressionException::fromInvalidBoolValueType($type, $expr);
    }

    private function evalBoolScalarTypeWithValue(ScalarType $type, Expression $ctx, mixed $value): bool
    {
        if (\is_bool($value)) {
            return $value;
        }

        throw ExpressionException::fromInvalidBoolValueTypeWithValue($type, $ctx, $value);
    }

    private function evalFloatScalarType(ScalarType $type, Expression $expr): float
    {
        if ($expr instanceof FloatLiteralNode) {
            return $expr->value;
        }

        if ($this->config->castIntToFloat && $expr instanceof IntLiteralNode) {
            return (float)$expr->value;
        }

        /** @psalm-suppress MixedAssignment */
        if ($expr instanceof VariableNode) {
            $value = $this->fetchVariable($expr);

            return $this->evalFloatScalarTypeWithValue($type, $expr, $value);
        }

        throw ExpressionException::fromInvalidFloatValueType($type, $expr);
    }

    private function evalFloatScalarTypeWithValue(ScalarType $type, Expression $ctx, mixed $value): float
    {
        if (\is_float($value)) {
            return $value;
        }

        if ($this->config->castIntToFloat && \is_int($value)) {
            return (float)$value;
        }

        throw ExpressionException::fromInvalidFloatValueTypeWithValue($type, $ctx, $value);
    }

    private function evalIntScalarType(ScalarType $type, Expression $expr): int
    {
        if ($expr instanceof IntLiteralNode) {
            return $expr->value;
        }

        /** @psalm-suppress MixedAssignment */
        if ($expr instanceof VariableNode) {
            $value = $this->fetchVariable($expr);

            return $this->evalIntScalarTypeWithValue($type, $expr, $value);
        }

        throw ExpressionException::fromInvalidIntValueType($type, $expr);
    }

    private function evalIntScalarTypeWithValue(ScalarType $type, Expression $ctx, mixed $value): int
    {
        if (\is_int($value)) {
            return $value;
        }

        throw ExpressionException::fromInvalidIntValueTypeWithValue($type, $ctx, $value);
    }

    private function evalStringScalarType(ScalarType $type, Expression $expr): string
    {
        if ($expr instanceof StringLiteralNode) {
            return $expr->value;
        }

        if ($this->config->castScalarToString) {
            switch (true) {
                case $expr instanceof BoolLiteralNode:
                    return $expr->representation ?? ($expr->value ? 'true' : 'false');
                case $expr instanceof ConstLiteralNode:
                    return $expr->value->value;
                case $expr instanceof FloatLiteralNode:
                case $expr instanceof IntLiteralNode:
                    return $expr->representation ?? (string)$expr->value;
                case $expr instanceof NullLiteralNode:
                    return 'null';
            }
        }

        /** @psalm-suppress MixedAssignment */
        if ($expr instanceof VariableNode) {
            $value = $this->fetchVariable($expr);

            return $this->evalStringScalarTypeWithValue($type, $expr, $value);
        }

        throw ExpressionException::fromInvalidStringValueType($type, $expr);
    }

    private function evalStringScalarTypeWithValue(ScalarType $type, Expression $ctx, mixed $value): string
    {
        if (\is_string($value)) {
            return $value;
        }

        if ($this->config->castScalarToString) {
            switch (true) {
                case \is_bool($value):
                    return $value ? 'true' : 'false';
                case \is_float($value):
                case \is_int($value):
                    return (string)$value;
                case $value === null:
                    return 'null';
            }
        }

        throw ExpressionException::fromInvalidStringValueTypeWithValue($type, $ctx, $value);
    }

    private function evalInputType(InputObjectType $type, Expression $expr): InputObject
    {
        /** @psalm-suppress MixedAssignment */
        if ($expr instanceof VariableNode) {
            $value = $this->fetchVariable($expr);

            return $this->evalInputTypeWithValue($type, $expr, $value);
        }

        if (!$expr instanceof ObjectLiteralNode) {
            throw ExpressionException::fromInvalidInputValueType($type, $expr);
        }

        $result = [];

        foreach ($expr->fields as $node) {
            $field = $type->getField($node->key->value);

            if ($field === null) {
                throw ExpressionException::fromInvalidInputField($type, $node);
            }

            /** @psalm-suppress MixedAssignment : Okay */
            $result[$node->key->value] = $this->eval($field->getType(), $node->value);
        }

        $this->queue->push(new EvaluateInputObjectValue(
            config: $this->config,
            node: $expr,
            input: $type,
            defaults: $result,
        ));

        return new InputObject($type, $result);
    }

    /**
     * @psalm-suppress MixedAssignment : Okay
     */
    private function evalInputTypeWithValue(InputObjectType $type, Expression $expr, mixed $object): InputObject
    {
        if (!\is_iterable($object)) {
            throw ExpressionException::fromInvalidInputValueTypeWithValue($type, $expr, $object);
        }

        /** @var array<non-empty-string, mixed> $result */
        $result = [];

        foreach ($object as $key => $value) {
            if (!\is_string($key) && !$key instanceof \Stringable) {
                throw ExpressionException::fromInvalidInputFieldWithValue($type, $expr, $key);
            }

            $field = $type->getField(
                $key = (string)$key,
            );

            if ($field === null) {
                throw ExpressionException::fromInvalidInputFieldWithValue($type, $expr, $key);
            }

            /** @psalm-suppress MixedAssignment : Okay */
            $result[$key] = $this->evalWithValue($field->getType(), $expr, $value);
        }

        /**
         * @psalm-suppress ArgumentTypeCoercion
         */
        $this->queue->push(new EvaluateInputObjectValue(
            config: $this->config,
            node: $expr,
            input: $type,
            defaults: $result,
        ));

        return new InputObject($type, $result);
    }

    private function evalNonNullType(NonNullType $type, Expression $expr): mixed
    {
        if ($expr instanceof NullLiteralNode) {
            throw ExpressionException::fromInvalidNonNullValue($type, $expr);
        }

        /** @psalm-suppress MixedAssignment */
        if ($expr instanceof VariableNode) {
            $value = $this->fetchVariable($expr);

            return $this->evalNonNullTypeWithValue($type, $expr, $value);
        }

        return $this->eval($type->getOfType(), $expr);
    }

    private function evalNonNullTypeWithValue(NonNullType $type, Expression $ctx, mixed $value): mixed
    {
        if ($value === null) {
            throw ExpressionException::fromInvalidNonNullValue($type, $ctx);
        }

        return $this->evalWithValue($type->getOfType(), $ctx, $value);
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

        /** @psalm-suppress MixedAssignment */
        if ($expr instanceof VariableNode) {
            $value = $this->fetchVariable($expr);

            return $this->evalListTypeWithValue($type, $expr, $value);
        }

        throw ExpressionException::fromInvalidListValue($type, $expr);
    }

    /**
     * @psalm-suppress MixedAssignment : Okay
     */
    private function evalListTypeWithValue(ListType $type, Expression $ctx, mixed $value): array
    {
        if (\is_iterable($value)) {
            $result = [];

            foreach ($value as $literal) {
                $result[] = $this->evalWithValue($type->getOfType(), $ctx, $literal);
            }

            return $result;
        }

        throw ExpressionException::fromInvalidListValueWithValue($type, $ctx, $value);
    }
}
