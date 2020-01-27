<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Extension\Type;

use Railt\SDL\Frontend\Ast\Definition\FieldDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ImplementedInterfaceNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Description;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;

/**
 * Class ObjectTypeExtensionNode
 *
 * <code>
 *  export interface ObjectTypeExtensionNode {
 *      readonly kind: 'ObjectTypeExtension';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly interfaces?: ReadonlyArray<NamedTypeNode>;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly fields?: ReadonlyArray<FieldDefinitionNode>;
 *  }
 * </code>
 */
class ObjectTypeExtensionNode extends TypeExtensionNode
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
        $object = new static($children[0]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof FieldDefinitionNode:
                    $object->fields[] = $child;
                    break;

                case $child instanceof DirectiveNode:
                    $object->directives[] = $child;
                    break;

                case $child instanceof ImplementedInterfaceNode:
                    $object->interfaces[] = $child;
                    break;
            }
        }

        return $object;
    }
}
