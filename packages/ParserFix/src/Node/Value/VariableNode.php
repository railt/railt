<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Value;

use Railt\Parser\Node\NameNode;

/**
 * Class VariableNode
 *
 * <code>
 *  export type VariableNode = {
 *      +kind: 'Variable',
 *      +loc?: Location,
 *      +name: NameNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L232
 */
class VariableNode extends ValueNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * VariableNode constructor.
     *
     * @param NameNode $name
     */
    public function __construct(NameNode $name)
    {
        $this->name = $name;
    }
}
