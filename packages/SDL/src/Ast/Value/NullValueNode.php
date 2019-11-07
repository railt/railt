<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Value;

/**
 * Class NullValueNode
 *
 * <code>
 *  export type NullValueNode = {
 *      +kind: 'NullValue',
 *      +loc?: Location,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L341
 */
class NullValueNode extends ValueNode
{
    /**
     * @return mixed|null
     */
    public function toNative()
    {
        return null;
    }
}
