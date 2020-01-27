<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition\Type;

use Railt\SDL\Frontend\Ast\Definition\TypeSystemDefinitionNode;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Identifier;
use Railt\TypeSystem\Value\StringValue;

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
     * @var Identifier
     */
    public Identifier $name;

    /**
     * @var StringValue|null
     */
    public ?StringValue $description = null;

    /**
     * @var DirectiveNode[]
     */
    public array $directives = [];

    /**
     * TypeDefinitionNode constructor.
     *
     * @param Identifier $name
     */
    public function __construct(Identifier $name)
    {
        $this->name = $name;
    }
}
