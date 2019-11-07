<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\SDL\Ast\Generic\FieldDefinitionCollection;
use Railt\SDL\Ast\Generic\InterfaceImplementsCollection;

/**
 * Class InterfaceTypeDefinitionNode
 *
 * <code>
 *  export interface InterfaceTypeDefinitionNode {
 *      readonly kind: 'InterfaceTypeDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly interfaces?: ReadonlyArray<NamedTypeNode>;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly fields?: ReadonlyArray<FieldDefinitionNode>;
 *  }
 * </code>
 */
class InterfaceTypeDefinitionNode extends TypeDefinitionNode
{
    /**
     * @var InterfaceImplementsCollection|null
     */
    public ?InterfaceImplementsCollection $interfaces = null;

    /**
     * @var FieldDefinitionCollection|null
     */
    public ?FieldDefinitionCollection $fields = null;
}
