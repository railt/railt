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
use Railt\SDL\Ast\Value\StringValueNode;
use Railt\SDL\Ast\Generic\DirectiveCollection;

/**
 * Class TypeDefinitionNode
 *
 * <code>
 *  export type TypeDefinitionNode =
 *      | ScalarTypeDefinitionNode
 *      | ObjectTypeDefinitionNode
 *      | InterfaceTypeDefinitionNode
 *      | UnionTypeDefinitionNode
 *      | EnumTypeDefinitionNode
 *      | InputObjectTypeDefinitionNode
 *  ;
 * </code>
 */
abstract class TypeDefinitionNode extends TypeSystemDefinitionNode
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
     * TypeDefinitionNode constructor.
     *
     * @param IdentifierNode $name
     */
    public function __construct(IdentifierNode $name)
    {
        $this->name = $name;
    }
}
