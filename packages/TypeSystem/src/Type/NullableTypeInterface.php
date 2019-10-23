<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem\Type;

/**
 * These types can all accept null as a value.
 *
 * <code>
 *  export type GraphQLNullableType =
 *      | GraphQLScalarType
 *      | GraphQLObjectType
 *      | GraphQLInterfaceType
 *      | GraphQLUnionType
 *      | GraphQLEnumType
 *      | GraphQLInputObjectType
 *      | GraphQLList<any>
 *  ;
 * </code>
 */
interface NullableTypeInterface
{

}
