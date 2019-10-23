<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast\TypeSystem\Definition;

use Railt\Parser\Ast\NameNode;
use Railt\Parser\Ast\Value\StringValueNode;
use Railt\Parser\Ast\Generic\DirectiveLocationCollection;
use Railt\Parser\Ast\TypeSystem\TypeSystemDefinitionNode;
use Railt\Parser\Ast\Generic\InputValueDefinitionCollection;

/**
 * Class DirectiveDefinitionNode
 *
 * <code>
 *  export type DirectiveDefinitionNode = {
 *      +kind: 'DirectiveDefinition',
 *      +loc?: Location,
 *      +description?: StringValueNode,
 *      +name: NameNode,
 *      +arguments?: $ReadOnlyArray<InputValueDefinitionNode>,
 *      +repeatable: boolean,
 *      +locations: $ReadOnlyArray<NameNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L537
 */
class DirectiveDefinitionNode extends TypeSystemDefinitionNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * @var bool
     */
    public bool $repeatable;

    /**
     * @var DirectiveLocationCollection
     */
    public DirectiveLocationCollection $locations;

    /**
     * @var StringValueNode|null
     */
    public ?StringValueNode $description = null;

    /**
     * @var InputValueDefinitionCollection|null
     */
    public ?InputValueDefinitionCollection $arguments = null;

    /**
     * TypeDefinitionNode constructor.
     *
     * @param NameNode $name
     * @param DirectiveLocationCollection $locations
     * @param bool $repeatable
     */
    public function __construct(NameNode $name, DirectiveLocationCollection $locations, bool $repeatable = false)
    {
        $this->name = $name;
        $this->locations = $locations;
        $this->repeatable = $repeatable;
    }
}
