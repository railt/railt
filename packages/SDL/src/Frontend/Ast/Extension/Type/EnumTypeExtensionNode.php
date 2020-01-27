<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Extension\Type;

use Railt\SDL\Frontend\Ast\Definition\EnumValueDefinitionNode;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class EnumTypeExtensionNode
 *
 * <code>
 *  export interface EnumTypeExtensionNode {
 *      readonly kind: 'EnumTypeExtension';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly values?: ReadonlyArray<EnumValueDefinitionNode>;
 *  }
 * </code>
 */
class EnumTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var EnumValueDefinitionNode[]
     */
    public array $values = [];

    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        $enum = new static($children[0]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof DirectiveNode:
                    $enum->directives[] = $child;
                    break;

                case $child instanceof EnumValueDefinitionNode:
                    $enum->values[] = $child;
                    break;
            }
        }

        return $enum;
    }
}
