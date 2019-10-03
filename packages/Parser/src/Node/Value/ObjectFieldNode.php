<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Value;

use Railt\Parser\Node\Node;
use Railt\Parser\Node\NameNode;

/**
 * Class ObjectFieldNode
 *
 * <code>
 *  export type ObjectFieldNode = {
 *      +kind: 'ObjectField',
 *      +loc?: Location,
 *      +name: NameNode,
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
     * @var NameNode
     */
    public NameNode $name;

    /**
     * @var ValueNode
     */
    public ValueNode $value;

    /**
     * ObjectFieldNode constructor.
     *
     * @param NameNode $name
     * @param ValueNode $value
     */
    public function __construct(NameNode $name, ValueNode $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
