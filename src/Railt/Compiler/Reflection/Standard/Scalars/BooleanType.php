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
 * The Boolean standard scalar implementation.
 *
 * @see http://facebook.github.io/graphql/#sec-Boolean
 */
final class BooleanType extends StringType
{
    /**
     * The Boolean scalar public name constant.
     * This name will be used in the future as the
     * type name available for use in our schema.
     */
    protected const SCALAR_TYPE_NAME = 'Boolean';

    /**
     * Short Boolean scalar public description.
     */
    protected const TYPE_DESCRIPTION = 'The `Boolean` scalar type represents `true` or `false`.';

    /**
     * @param mixed|bool $value
     * @return bool
     */
    public function isCompatible($value): bool
    {
        return \is_bool($value);
    }
}
