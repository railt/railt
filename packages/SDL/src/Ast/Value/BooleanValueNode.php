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
 * Class BooleanValueNode
 *
 * <code>
 *  export type BooleanValueNode = {
 *      +kind: 'BooleanValue',
 *      +loc?: Location,
 *      +value: boolean,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L334
 */
class BooleanValueNode extends ValueNode
{
    /**
     * @var bool
     */
    public bool $value;

    /**
     * BooleanValueNode constructor.
     *
     * @param $value
     */
    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return static
     */
    public static function parse(string $value): self
    {
        return new static(\strtolower($value) === 'true');
    }

    /**
     * @return bool
     */
    public function toNative(): bool
    {
        return $this->value;
    }
}
