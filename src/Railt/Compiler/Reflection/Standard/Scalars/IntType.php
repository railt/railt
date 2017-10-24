<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Standard\Scalars;

/**
 * The Float standard scalar implementation.
 *
 * @see http://facebook.github.io/graphql/#sec-Int
 */
final class IntType extends FloatType
{
    /**
     * The Int scalar public name constant.
     * This name will be used in the future as
     * the type name available for use in our GraphQL schema.
     */
    protected const SCALAR_TYPE_NAME = 'Int';

    /**
     * Short Int scalar public description.
     */
    protected const TYPE_DESCRIPTION = 'The `Int` scalar type represents non-fractional signed whole numeric
values. Int can represent values between -(2^31) and 2^31 - 1.';
}
