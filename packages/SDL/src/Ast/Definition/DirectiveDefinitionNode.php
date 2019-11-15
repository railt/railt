<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\SDL\Ast\Name\IdentifierNode;
use Railt\SDL\Ast\Value\StringValueNode;
use Railt\SDL\Ast\Generic\DirectiveLocationCollection;
use Railt\SDL\Ast\Generic\ArgumentDefinitionCollection;
use Railt\SDL\Ast\Generic\InputFieldDefinitionCollection;

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
     * @var IdentifierNode
     */
    public IdentifierNode $name;

    /**
     * @var bool
     */
    public bool $repeatable;

    /**
     * @var DirectiveLocationCollection|IdentifierNode[]
     */
    public DirectiveLocationCollection $locations;

    /**
     * @var StringValueNode|null
     */
    public ?StringValueNode $description = null;

    /**
     * @var ArgumentDefinitionCollection|null
     */
    public ?ArgumentDefinitionCollection $arguments = null;

    /**
     * TypeDefinitionNode constructor.
     *
     * @param IdentifierNode $name
     * @param DirectiveLocationCollection $locations
     * @param bool $repeatable
     */
    public function __construct(IdentifierNode $name, DirectiveLocationCollection $locations, bool $repeatable = false)
    {
        $this->name = $name;
        $this->locations = $locations;
        $this->repeatable = $repeatable;
    }
}
