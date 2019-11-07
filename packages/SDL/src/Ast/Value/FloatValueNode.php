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
 * Class FloatValueNode
 *
 * <code>
 *  export type FloatValueNode = {
 *      +kind: 'FloatValue',
 *      +loc?: Location,
 *      +value: string,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L319
 */
class FloatValueNode extends ValueNode
{
    /**
     * @var float
     */
    public float $value;

    /**
     * FloatValueNode constructor.
     *
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return static
     */
    public static function parse(string $value): self
    {
        return new static((float)$value);
    }

    /**
     * @return float
     */
    public function toNative(): float
    {
        return $this->value;
    }
}
