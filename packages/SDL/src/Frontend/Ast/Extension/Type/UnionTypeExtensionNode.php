<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Extension\Type;

use Railt\SDL\Frontend\Ast\Definition\Type\UnionMemberNode;
use Railt\SDL\Frontend\Ast\Definition\Type\UnionTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Description;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;

/**
 * Class UnionTypeExtensionNode
 *
 * <code>
 *  export interface UnionTypeExtensionNode {
 *      readonly kind: 'UnionTypeExtension';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly types?: ReadonlyArray<NamedTypeNode>;
 *  }
 * </code>
 */
class UnionTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var UnionMemberNode[]
     */
    public array $types = [];

    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        $union = new static($children[0]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof DirectiveNode:
                    $union->directives[] = $child;
                    break;

                case $child instanceof UnionMemberNode:
                    $union->types[] = $child;
                    break;
            }
        }

        return $union;
    }
}
