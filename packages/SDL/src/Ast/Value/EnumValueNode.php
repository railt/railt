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
 * Class EnumValueNode
 *
 * <code>
 *  export type EnumValueNode = {
 *      +kind: 'EnumValue',
 *      +loc?: Location,
 *      +value: string,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L347
 */
class EnumValueNode extends ValueNode
{
    /**
     * @var string
     */
    public string $value;

    /**
     * EnumValueNode constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function toNative(): string
    {
        return $this->value;
    }
}
