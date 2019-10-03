<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\TypeSystem\Definition;

use Railt\Parser\Node\NameNode;
use Railt\Parser\Node\Value\StringValueNode;
use Railt\Parser\Node\Generic\DirectiveLocationCollection;
use Railt\Parser\Node\TypeSystem\TypeSystemDefinitionNode;
use Railt\Parser\Node\Generic\InputValueDefinitionCollection;

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
