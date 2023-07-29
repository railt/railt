<?php

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Railt\SDL\Node\Expression\Expression;
use Railt\SDL\Node\Expression\Literal\ConstLiteralNode;
use Railt\SDL\Node\Expression\Literal\ObjectLiteralFieldNode;
use Railt\SDL\Node\Expression\VariableNode;
use Railt\TypeSystem\Definition\Type\EnumType;
use Railt\TypeSystem\Definition\Type\InputObjectType;
use Railt\TypeSystem\Definition\Type\ScalarType;
use Railt\TypeSystem\ListType;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\TypeInterface;

/**
 * @psalm-suppress MoreSpecificReturnType
 * @psalm-suppress LessSpecificReturnStatement
 */
class ExpressionException extends CompilationException
{
    public const CODE_UNPROCESSABLE = 0x01;
    public const CODE_UNDEFINED_VARIABLE = 0x02;
    public const CODE_ENUM_INVALID_TYPE = 0x03;
    public const CODE_ENUM_INVALID_VALUE = 0x04;
    public const CODE_BOOL_INVALID_TYPE = 0x05;
    public const CODE_FLOAT_INVALID_TYPE = 0x06;
    public const CODE_INT_INVALID_TYPE = 0x07;
    public const CODE_STRING_INVALID_TYPE = 0x08;
    public const CODE_INPUT_INVALID_TYPE = 0x09;
    public const CODE_INPUT_INVALID_FIELD = 0x10;
    public const CODE_NON_NULL_INVALID_TYPE = 0x11;
    public const CODE_LIST_INVALID_TYPE = 0x12;
    public const CODE_IDENTIFIER_INVALID_TYPE = 0x13;

    public static function fromUnprocessableExpr(TypeInterface $type, Expression $expr): static
    {
        return self::fromUnprocessableExprWithValue($type, $expr, $expr);
    }

    public static function fromUnprocessableExprWithValue(TypeInterface $type, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Cannot check non-supported type %s compatibility with %s expression', [
            (string)$type,
            self::valueToString($expr),
        ]);

        return static::create($message, $expr, self::CODE_UNPROCESSABLE);
    }

    public static function fromUndefinedVariable(VariableNode $expr): static
    {
        $message = \sprintf('Undefined variable %s', (string)$expr);

        return static::create($message, $expr, self::CODE_UNDEFINED_VARIABLE);
    }

    public static function fromInvalidEnumValueType(EnumType $enum, Expression $expr): static
    {
        return self::fromInvalidEnumValueTypeWithValue($enum, $expr, $expr);
    }

    public static function fromInvalidEnumValueTypeWithValue(EnumType $enum, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Cannot pass non-enum value %s to %s', [
            self::valueToString($value),
            (string)$enum,
        ]);

        return static::create($message, $expr, self::CODE_ENUM_INVALID_TYPE);
    }

    public static function fromInvalidEnumValue(EnumType $enum, ConstLiteralNode $expr): static
    {
        return self::fromInvalidEnumValueWithValue($enum, $expr, $expr);
    }

    public static function fromInvalidEnumValueWithValue(EnumType $enum, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Invalid enum value %s of %s', [
            self::valueToString($value),
            (string)$enum,
        ]);

        return static::create($message, $expr, self::CODE_ENUM_INVALID_VALUE);
    }


    public static function fromInvalidBoolValueType(ScalarType $scalar, Expression $expr): static
    {
        return self::fromInvalidBoolValueTypeWithValue($scalar, $expr, $expr);
    }

    public static function fromInvalidBoolValueTypeWithValue(ScalarType $scalar, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Cannot pass non-bool value %s to %s', [
            self::valueToString($value),
            (string)$scalar,
        ]);

        return static::create($message, $expr, self::CODE_BOOL_INVALID_TYPE);
    }

    public static function fromInvalidFloatValueType(ScalarType $scalar, Expression $expr): static
    {
        return self::fromInvalidFloatValueTypeWithValue($scalar, $expr, $expr);
    }

    public static function fromInvalidFloatValueTypeWithValue(ScalarType $scalar, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Cannot pass non-float value %s to %s', [
            self::valueToString($value),
            (string)$scalar,
        ]);

        return static::create($message, $expr, self::CODE_FLOAT_INVALID_TYPE);
    }


    public static function fromInvalidIntValueType(ScalarType $scalar, Expression $expr): static
    {
        return self::fromInvalidIntValueTypeWithValue($scalar, $expr, $expr);
    }

    public static function fromInvalidIntValueTypeWithValue(ScalarType $scalar, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Cannot pass non-int value %s to %s', [
            self::valueToString($value),
            (string)$scalar,
        ]);

        return static::create($message, $expr, self::CODE_INT_INVALID_TYPE);
    }

    public static function fromInvalidStringValueType(ScalarType $scalar, Expression $expr): static
    {
        return self::fromInvalidStringValueTypeWithValue($scalar, $expr, $expr);
    }

    public static function fromInvalidStringValueTypeWithValue(ScalarType $scalar, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Cannot pass non-string value %s to %s', [
            self::valueToString($value),
            (string)$scalar,
        ]);

        return static::create($message, $expr, self::CODE_STRING_INVALID_TYPE);
    }

    public static function fromInvalidInputValueType(InputObjectType $input, Expression $expr): static
    {
        return self::fromInvalidInputValueTypeWithValue($input, $expr, $expr);
    }

    public static function fromInvalidInputValueTypeWithValue(InputObjectType $input, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Cannot pass non-object value %s to %s', [
            self::valueToString($value),
            (string)$input,
        ]);

        return static::create($message, $expr, self::CODE_INPUT_INVALID_TYPE);
    }

    public static function fromInvalidInputFieldWithValue(InputObjectType $input, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Invalid input object field %s of %s', [
            self::valueToString($value),
            (string)$input,
        ]);

        return static::create($message, $expr, self::CODE_INPUT_INVALID_FIELD);
    }

    public static function fromInvalidInputField(InputObjectType $input, ObjectLiteralFieldNode $field): static
    {
        $message = \vsprintf('Unknown input object field "%s" of %s', [
            $field->key->value,
            (string)$input,
        ]);

        return static::create($message, $field, self::CODE_INPUT_INVALID_FIELD);
    }

    public static function fromInvalidNonNullValue(NonNullType $type, Expression $expr): static
    {
        return static::fromInvalidNonNullValueWithValue($type, $expr, $expr);
    }

    public static function fromInvalidNonNullValueWithValue(NonNullType $type, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Cannot pass %s value to non-null type %s', [
            self::valueToString($value),
            (string)$type,
        ]);

        return static::create($message, $expr, self::CODE_NON_NULL_INVALID_TYPE);
    }

    public static function fromInvalidListValue(ListType $type, Expression $expr): static
    {
        return self::fromInvalidListValueWithValue($type, $expr, $expr);
    }

    public static function fromInvalidListValueWithValue(ListType $type, Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Cannot pass %s value to non-null type %s', [
            self::valueToString($value),
            (string)$type,
        ]);

        return static::create($message, $expr, self::CODE_LIST_INVALID_TYPE);
    }

    public static function fromInvalidIdentifier(Expression $expr): static
    {
        return self::fromInvalidIdentifierWithValue($expr, $expr);
    }

    public static function fromInvalidIdentifierWithValue(Expression $expr, mixed $value): static
    {
        $message = \vsprintf('Cannot pass non-string value %s to identifier', [
            self::valueToString($value),
        ]);

        return static::create($message, $expr, self::CODE_IDENTIFIER_INVALID_TYPE);
    }

    private static function valueToString(mixed $value): string
    {
        if ($value instanceof Expression) {
            return (string)$value;
        }

        if (\is_scalar($value)) {
            return \vsprintf('%s(%s)', [
                \get_debug_type($value),
                \var_export($value, true),
            ]);
        }

        if (\is_array($value)) {
            return \vsprintf('%sarray(%d) { ... }', [
                \array_is_list($value) ? '' : 'object-like ',
                \count($value),
            ]);
        }

        if (\is_object($value)) {
            return \sprintf('object(%s)', $value::class);
        }

        return \get_debug_type($value);
    }
}
