<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition;

use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\SDL\Frontend\Ast\Description;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Identifier;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\TypeNode;
use Railt\TypeSystem\Value\StringValue;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class InputValueDefinitionNode
 *
 * <code>
 *  export interface InputValueDefinitionNode {
 *      readonly kind: 'InputValueDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly type: TypeNode;
 *      readonly defaultValue?: ValueNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *  }
 * </code>
 */
class InputFieldDefinitionNode extends DefinitionNode
{
    /**
     * @var Identifier
     */
    public Identifier $name;

    /**
     * @var TypeNode
     */
    public TypeNode $type;

    /**
     * @var StringValue|null
     */
    public ?StringValue $description = null;

    /**
     * @var DirectiveNode[]
     */
    public array $directives = [];

    /**
     * @var ValueInterface|null
     */
    public ?ValueInterface $defaultValue = null;

    /**
     * TypeDefinitionNode constructor.
     *
     * @param Identifier $name
     * @param TypeNode $type
     */
    public function __construct(Identifier $name, TypeNode $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        $field = new static($children[1], $children[2]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof Description:
                    $field->description = $child->value;
                    break;

                case $child instanceof DirectiveNode:
                    $field->directives[] = $child;
                    break;

                case $child instanceof ValueInterface:
                    $field->defaultValue = $child;
                    break;
            }
        }

        return $field;
    }
}
