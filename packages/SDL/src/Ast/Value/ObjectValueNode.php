<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Value;

use Railt\SDL\Ast\Generic\ObjectFieldCollection;

/**
 * Class ObjectValueNode
 *
 * <code>
 *  export type ObjectValueNode = {
 *      +kind: 'ObjectValue',
 *      +loc?: Location,
 *      +fields: $ReadOnlyArray<ObjectFieldNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L361
 */
class ObjectValueNode extends ValueNode
{
    /**
     * @var ObjectFieldCollection|ObjectFieldNode[]
     */
    public ObjectFieldCollection $fields;

    /**
     * ObjectValueNode constructor.
     *
     * @param ObjectFieldCollection $fields
     */
    public function __construct(ObjectFieldCollection $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function toNative(): array
    {
        $result = [];

        foreach ($this->fields as $field) {
            $result[$field->name->value] = $field->value->toNative();
        }

        return $result;
    }
}
