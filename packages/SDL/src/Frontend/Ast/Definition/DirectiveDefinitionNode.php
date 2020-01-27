<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition;

use Railt\SDL\Frontend\Ast\Description;
use Railt\SDL\Frontend\Ast\Identifier;
use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\StringValue;

/**
 * Class DirectiveDefinitionNode
 *
 * <code>
 *  export interface DirectiveDefinitionNode {
 *      readonly kind: 'DirectiveDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly arguments?: ReadonlyArray<InputValueDefinitionNode>;
 *      readonly repeatable: boolean;
 *      readonly locations: ReadonlyArray<IdentifierNode>;
 *  }
 * </code>
 */
class DirectiveDefinitionNode extends TypeSystemDefinitionNode
{
    /**
     * @var Identifier
     */
    public Identifier $name;

    /**
     * @var DirectiveDefinitionIsRepeatableNode|null
     */
    public ?DirectiveDefinitionIsRepeatableNode $repeatable = null;

    /**
     * @var DirectiveDefinitionLocationNode[]
     */
    public array $locations = [];

    /**
     * @var StringValue|null
     */
    public ?StringValue $description = null;

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
        $directive = new static($children[1]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof Description:
                    $directive->description = $child->value;
                    break;

                case $child instanceof DirectiveDefinitionLocationNode:
                    $directive->locations[] = $child;
                    break;

                case $child instanceof DirectiveDefinitionIsRepeatableNode:
                    $directive->repeatable = $child;
                    break;

                case $child instanceof ArgumentDefinitionNode:
                    $directive->arguments[] = $child;
                    break;
            }
        }

        return $directive;
    }
}
