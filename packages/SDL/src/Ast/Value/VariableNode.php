<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Value;

use Railt\SDL\Ast\Name\IdentifierNode;
use Railt\SDL\Ast\DefinitionNode;

/**
 * Class VariableNode
 *
 * <code>
 *  export type VariableNode = {
 *      +kind: 'Variable',
 *      +loc?: Location,
 *      +name: IdentifierNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L232
 */
class VariableNode extends DefinitionNode
{
    /**
     * @var IdentifierNode
     */
    public IdentifierNode $name;

    /**
     * VariableNode constructor.
     *
     * @param IdentifierNode $name
     */
    public function __construct(IdentifierNode $name)
    {
        $this->name = $name;
    }
}
