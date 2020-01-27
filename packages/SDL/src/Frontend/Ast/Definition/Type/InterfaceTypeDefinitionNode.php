<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition\Type;

use Railt\SDL\Frontend\Ast\Definition\FieldDefinitionNode;
use Railt\SDL\Frontend\Ast\Description;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;

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
     * @var ImplementedInterfaceNode[]
     */
    public array $interfaces = [];

    /**
     * @var FieldDefinitionNode[]
     */
    public array $fields = [];

    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        $interface = new static($children[1]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof Description:
                    $interface->description = $child->value;
                    break;

                case $child instanceof FieldDefinitionNode:
                    $interface->fields[] = $child;
                    break;

                case $child instanceof DirectiveNode:
                    $interface->directives[] = $child;
                    break;

                case $child instanceof ImplementedInterfaceNode:
                    $interface->interfaces[] = $child;
                    break;
            }
        }

        return $interface;
    }
}
