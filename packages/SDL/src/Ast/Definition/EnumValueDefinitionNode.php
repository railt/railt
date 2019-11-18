<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\SDL\Ast\Name\IdentifierNode;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Value\StringValueNode;
use Railt\SDL\Ast\Generic\DirectiveCollection;

/**
 * Class EnumValueDefinitionNode
 *
 * <code>
 *  export interface EnumValueDefinitionNode {
 *      readonly kind: 'EnumValueDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *  }
 * </code>
 */
class EnumValueDefinitionNode extends DefinitionNode
{
    /**
     * @var IdentifierNode
     */
    public IdentifierNode $name;

    /**
     * @var StringValueNode|null
     */
    public ?StringValueNode $description = null;

    /**
     * @var DirectiveCollection|null
     */
    public ?DirectiveCollection $directives = null;

    /**
     * EnumValueDefinitionNode constructor.
     *
     * @param IdentifierNode $name
     */
    public function __construct(IdentifierNode $name)
    {
        $this->name = $name;
    }
}
