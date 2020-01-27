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
 * Class UnionTypeDefinitionNode
 */
class UnionTypeDefinitionNode extends TypeDefinitionNode
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
        $union = new static($children[1]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof Description:
                    $union->description = $child->value;
                    break;

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
