<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Extension;

use Railt\SDL\Ast\Name\IdentifierNode;
use Railt\SDL\Ast\Value\StringValueNode;
use Railt\SDL\Ast\Generic\DirectiveCollection;

/**
 * Class TypeExtensionNode
 *
 * <code>
 *  export type TypeExtensionNode =
 *      | ScalarTypeExtensionNode
 *      | ObjectTypeExtensionNode
 *      | InterfaceTypeExtensionNode
 *      | UnionTypeExtensionNode
 *      | EnumTypeExtensionNode
 *      | InputObjectTypeExtensionNode
 *  ;
 * </code>
 */
abstract class TypeExtensionNode extends TypeSystemExtensionNode
{
    /**
     * @var IdentifierNode
     */
    public IdentifierNode $name;

    /**
     * @var DirectiveCollection|null
     */
    public ?DirectiveCollection $directives = null;

    /**
     * TypeExtensionNode constructor.
     *
     * @param IdentifierNode $name
     */
    public function __construct(IdentifierNode $name)
    {
        $this->name = $name;
    }
}
