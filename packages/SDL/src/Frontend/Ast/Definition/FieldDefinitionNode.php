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
use Railt\SDL\Frontend\Ast\Generic\ArgumentDefinitionCollection;
use Railt\SDL\Frontend\Ast\Generic\DirectiveCollection;
use Railt\SDL\Frontend\Ast\Identifier;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\TypeNode;
use Railt\TypeSystem\Value\StringValue;

/**
 * <code>
 *  export interface FieldDefinitionNode {
 *      readonly kind: 'FieldDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly arguments?: ReadonlyArray<InputValueDefinitionNode>;
 *      readonly type: TypeNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *  }
 * </code>
 */
class FieldDefinitionNode extends DefinitionNode
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
     * @var ArgumentDefinitionNode[]
     */
    public array $arguments = [];

    /**
     * TypeDefinitionNode constructor.
     *
     * @param Identifier $name
     */
    public function __construct(Identifier $name)
    {
        $this->name = $name;
    }

    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        $field = new static($children[1]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof Description:
                    $field->description = $child->value;
                    break;

                case $child instanceof TypeNode:
                    $field->type = $child;
                    break;

                case $child instanceof DirectiveNode:
                    $field->directives[] = $child;
                    break;

                case $child instanceof ArgumentDefinitionNode:
                    $field->arguments[] = $child;
                    break;
            }
        }

        return $field;
    }
}
