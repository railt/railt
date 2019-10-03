<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Executable\Definition;

use Railt\Parser\Node\Type\TypeNode;
use Railt\Parser\Node\DefinitionNode;
use Railt\Parser\Node\Value\ValueNode;
use Railt\Parser\Node\Value\VariableNode;
use Railt\Parser\Node\Generic\DirectiveCollection;

/**
 * Class VariableDefinitionNode
 *
 * <code>
 *  export type VariableDefinitionNode = {
 *      +kind: 'VariableDefinition',
 *      +loc?: Location,
 *      +variable: VariableNode,
 *      +type: TypeNode,
 *      +defaultValue?: ValueNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L222
 */
class VariableDefinitionNode extends DefinitionNode
{
    /**
     * @var TypeNode
     */
    public TypeNode $type;

    /**
     * @var VariableNode
     */
    public VariableNode $variable;

    /**
     * @var ValueNode|null
     */
    public ?ValueNode $defaultValue = null;

    /**
     * @var DirectiveCollection|null
     */
    public ?DirectiveCollection $directives = null;

    /**
     * VariableDefinitionNode constructor.
     *
     * @param TypeNode $type
     * @param VariableNode $name
     */
    public function __construct(VariableNode $name, TypeNode $type)
    {
        $this->variable = $name;
        $this->type = $type;
    }
}
