<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Extension\Type;

use Railt\SDL\Frontend\Ast\Definition\InputFieldDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InputObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Description;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class InputObjectTypeExtensionNode
 *
 * <code>
 *  export interface InputObjectTypeExtensionNode {
 *      readonly kind: 'InputObjectTypeExtension';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly fields?: ReadonlyArray<InputValueDefinitionNode>;
 *  }
 * </code>
 */
class InputObjectTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var InputFieldDefinitionNode[]
     */
    public array $fields = [];

    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        $input = new static($children[0]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof DirectiveNode:
                    $input->directives[] = $child;
                    break;

                case $child instanceof InputFieldDefinitionNode:
                    $input->fields[] = $child;
                    break;
            }
        }

        return $input;
    }
}
