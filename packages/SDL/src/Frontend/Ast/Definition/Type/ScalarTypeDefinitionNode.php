<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition\Type;

use Railt\SDL\Frontend\Ast\Description;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class ScalarTypeDefinitionNode
 *
 * <code>
 *  export interface ScalarTypeDefinitionNode {
 *      readonly kind: 'ScalarTypeDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *  }
 * </code>
 */
class ScalarTypeDefinitionNode extends TypeDefinitionNode
{
    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        $scalar = new static($children[1]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof Description:
                    $scalar->description = $child->value;
                    break;

                case $child instanceof DirectiveNode:
                    $scalar->directives[] = $child;
                    break;
            }
        }

        return $scalar;
    }
}
