<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Value;

use Railt\SDL\Ast\Node;
use Railt\SDL\Ast\Name\IdentifierNode;

/**
 * Class ObjectFieldNode
 *
 * <code>
 *  export type ObjectFieldNode = {
 *      +kind: 'ObjectField',
 *      +loc?: Location,
 *      +name: IdentifierNode,
 *      +value: ValueNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L368
 */
class ObjectFieldNode extends Node
{
    /**
     * @var IdentifierNode
     */
    public IdentifierNode $name;

    /**
     * @var ValueNode
     */
    public ValueNode $value;

    /**
     * ObjectFieldNode constructor.
     *
     * @param IdentifierNode $name
     * @param ValueNode $value
     */
    public function __construct(IdentifierNode $name, ValueNode $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
