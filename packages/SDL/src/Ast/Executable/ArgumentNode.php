<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Executable;

use Railt\SDL\Ast\Name\IdentifierNode;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Value\ValueNode;

/**
 * Class ArgumentNode
 *
 * <code>
 *  export interface ArgumentNode {
 *      readonly kind: 'Argument';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly value: ValueNode;
 *  }
 * </code>
 */
class ArgumentNode extends DefinitionNode
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
     * ArgumentNode constructor.
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
