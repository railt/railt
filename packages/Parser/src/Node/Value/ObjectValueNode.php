<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Value;

use Railt\Parser\Node\Generic\ObjectFieldCollection;

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
     * @var ObjectFieldCollection
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
}
