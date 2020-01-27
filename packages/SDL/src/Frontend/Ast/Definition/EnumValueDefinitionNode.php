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
use Railt\TypeSystem\Value\StringValue;

/**
 * Class EnumValueDefinitionNode
 *
 * <code>
 *  export interface EnumValueDefinitionNode {
 *      readonly kind: 'EnumValueDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *  }
 * </code>
 */
class EnumValueDefinitionNode extends DefinitionNode
{
    /**
     * @var Identifier
     */
    public Identifier $name;

    /**
     * @var StringValue|null
     */
    public ?StringValue $description = null;

    /**
     * @var DirectiveNode[]
     */
    public array $directives = [];

    /**
     * EnumValueDefinitionNode constructor.
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
        $value = new static($children[1]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof Description:
                    $value->description = $child->value;
                    break;

                case $child instanceof DirectiveNode:
                    $value->directives[] = $child;
                    break;
            }
        }

        return $value;
    }
}
