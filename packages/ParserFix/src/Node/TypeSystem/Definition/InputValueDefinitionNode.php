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
use Railt\Parser\Node\Type\TypeNode;
use Railt\Parser\Node\DefinitionNode;
use Railt\Parser\Node\Value\ValueNode;
use Railt\Parser\Node\Value\StringValueNode;
use Railt\Parser\Node\Generic\DirectiveCollection;

/**
 * Class InputValueDefinitionNode
 *
 * <code>
 *  export type InputValueDefinitionNode = {
 *      +kind: 'InputValueDefinition',
 *      +loc?: Location,
 *      +description?: StringValueNode,
 *      +name: NameNode,
 *      +type: TypeNode,
 *      +defaultValue?: ValueNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L475
 */
class InputValueDefinitionNode extends DefinitionNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * @var TypeNode
     */
    public TypeNode $type;

    /**
     * @var StringValueNode|null
     */
    public ?StringValueNode $description = null;

    /**
     * @var DirectiveCollection|null
     */
    public ?DirectiveCollection $directives = null;

    /**
     * @var ValueNode|null
     */
    public ?ValueNode $defaultValue = null;

    /**
     * TypeDefinitionNode constructor.
     *
     * @param NameNode $name
     * @param TypeNode $type
     */
    public function __construct(NameNode $name, TypeNode $type)
    {
        $this->name = $name;
        $this->type = $type;
    }
}
