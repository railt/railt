<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Extension\Type;

use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Extension\TypeSystemExtensionNode;
use Railt\SDL\Frontend\Ast\Identifier;

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
     * @var Identifier
     */
    public Identifier $name;

    /**
     * @var DirectiveNode[]
     */
    public array $directives = [];

    /**
     * TypeExtensionNode constructor.
     *
     * @param Identifier $name
     */
    public function __construct(Identifier $name)
    {
        $this->name = $name;
    }
}
