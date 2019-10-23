<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\TypeSystem\Type\ListOf;
use Railt\TypeSystem\Type\NonNull;
use Railt\TypeSystem\Type\EnumType;
use Railt\TypeSystem\Type\UnionType;
use Railt\TypeSystem\Type\ScalarType;
use Railt\TypeSystem\Type\ObjectType;
use Railt\TypeSystem\Type\TypeInterface;
use Railt\TypeSystem\Type\InterfaceType;
use Railt\TypeSystem\Type\InputObjectType;
use Railt\TypeSystem\Type\LeafTypeInterface;
use Railt\TypeSystem\Type\InputTypeInterface;
use Railt\TypeSystem\Type\NamedTypeInterface;
use Railt\TypeSystem\Type\OutputTypeInterface;
use Railt\TypeSystem\Type\AbstractTypeInterface;
use Railt\TypeSystem\Type\WrappingTypeInterface;
use Railt\TypeSystem\Type\NullableTypeInterface;
use Railt\TypeSystem\Type\CompositeTypeInterface;

/**
 * Class Assert
 */
final class Assert
{
    /**
     * <code>
     *  export function isType(type: any):
     *      type is GraphQLType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isType(object $type): bool
    {
        return $type instanceof TypeInterface;
    }

    /**
     * <code>
     *  export function isScalarType(type: any):
     *      type is GraphQLScalarType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isScalarType(object $type): bool
    {
        return $type instanceof ScalarType;
    }

    /**
     * <code>
     *  export function isObjectType(type: any):
     *      type is GraphQLObjectType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isObjectType(object $type): bool
    {
        return $type instanceof ObjectType;
    }

    /**
     * <code>
     *  export function isInterfaceType(type: any):
     *      type is GraphQLInterfaceType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isInterfaceType(object $type): bool
    {
        return $type instanceof InterfaceType;
    }

    /**
     * <code>
     *  export function isUnionType(type: any):
     *      type is GraphQLUnionType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isUnionType(object $type): bool
    {
        return $type instanceof UnionType;
    }

    /**
     * <code>
     *  export function isEnumType(type: any):
     *      type is GraphQLEnumType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isEnumType(object $type): bool
    {
        return $type instanceof EnumType;
    }

    /**
     * <code>
     *  export function isInputObjectType(type: any):
     *      type is GraphQLInputObjectType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isInputObjectType(object $type): bool
    {
        return $type instanceof InputObjectType;
    }

    /**
     * <code>
     *  export function isListType(type: any):
     *      type is GraphQLList<any>
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isListType(object $type): bool
    {
        return $type instanceof ListOf;
    }

    /**
     * <code>
     *  export function isNonNullType(type: any):
     *      type is GraphQLNonNull<any>
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isNonNullType(object $type): bool
    {
        return $type instanceof NonNull;
    }

    /**
     * <code>
     *  export function isInputType(type: any):
     *      type is GraphQLInputType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isInputType(object $type): bool
    {
        return $type instanceof InputTypeInterface;
    }

    /**
     * <code>
     *  export function isOutputType(type: any):
     *      type is GraphQLOutputType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isOutputType(object $type): bool
    {
        return $type instanceof OutputTypeInterface;
    }

    /**
     * <code>
     *  export function isLeafType(type: any):
     *      type is GraphQLLeafType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isLeafType(object $type): bool
    {
        return $type instanceof LeafTypeInterface;
    }

    /**
     * <code>
     *  export function isCompositeType(type: any):
     *      type is GraphQLCompositeType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isCompositeType(object $type): bool
    {
        return $type instanceof CompositeTypeInterface;
    }

    /**
     * <code>
     *  export function isAbstractType(type: any):
     *      type is GraphQLAbstractType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isAbstractType(object $type): bool
    {
        return $type instanceof AbstractTypeInterface;
    }

    /**
     * <code>
     *  export function isWrappingType(type: any):
     *      type is GraphQLWrappingType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isWrappingType(object $type): bool
    {
        return $type instanceof WrappingTypeInterface;
    }

    /**
     * <code>
     *  export function isNullableType(type: any):
     *      type is GraphQLNullableType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isNullableType(object $type): bool
    {
        return $type instanceof NullableTypeInterface;
    }

    /**
     * <code>
     *  export function isNamedType(type: any):
     *      type is GraphQLNamedType
     *  ;
     * </code>
     *
     * @param object $type
     * @return bool
     */
    public static function isNamedType(object $type): bool
    {
        return $type instanceof NamedTypeInterface;
    }

    /**
     * <code>
     *  export function isRequiredArgument(arg: GraphQLArgument):
     *      boolean
     *  ;
     * </code>
     *
     * @param Argument $arg
     * @return bool
     */
    public static function isRequiredArgument(Argument $arg): bool
    {
        return static::isNonNullType($arg->type) && $arg->defaultValue === null;
    }

    /**
     * <code>
     *  export function isRequiredArgument(field: GraphQLInputField):
     *      boolean
     *  ;
     * </code>
     *
     * @param InputField $field
     * @return bool
     */
    public static function isRequiredInputField(InputField $field): bool
    {
        return static::isNonNullType($field->type) && $field->defaultValue === null;
    }
}
