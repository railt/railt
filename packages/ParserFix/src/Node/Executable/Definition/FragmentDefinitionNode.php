<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Executable\Definition;

use Railt\Parser\Node\NameNode;
use Railt\Parser\Node\Type\NamedTypeNode;
use Railt\Parser\Node\Generic\DirectiveCollection;
use Railt\Parser\Node\Executable\ExecutableDefinitionNode;

/**
 * Class FragmentDefinitionNode
 *
 * <code>
 *  export type FragmentDefinitionNode = {
 *      +kind: 'FragmentDefinition',
 *      +loc?: Location,
 *      +name: NameNode,
 *      +typeCondition: NamedTypeNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +selectionSet: SelectionSetNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L286
 */
class FragmentDefinitionNode extends ExecutableDefinitionNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * @var NamedTypeNode
     */
    public NamedTypeNode $typeCondition;

    /**
     * @var DirectiveCollection|null
     */
    public ?DirectiveCollection $directives = null;

    /**
     * @var SelectionSetNode
     */
    public SelectionSetNode $selectionSet;

    /**
     * FragmentDefinitionNode constructor.
     *
     * @param NameNode $name
     * @param NamedTypeNode $type
     * @param SelectionSetNode $selection
     */
    public function __construct(NameNode $name, NamedTypeNode $type, SelectionSetNode $selection)
    {
        $this->name = $name;
        $this->typeCondition = $type;
        $this->selectionSet = $selection;
    }
}
