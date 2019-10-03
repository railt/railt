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
use Railt\Parser\Node\Executable\SelectionNode;

/**
 * Class InlineFragmentNode
 *
 * <code>
 *  export type InlineFragmentNode = {
 *      +kind: 'InlineFragment',
 *      +loc?: Location,
 *      +typeCondition?: NamedTypeNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +selectionSet: SelectionSetNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L277
 */
class InlineFragmentNode extends SelectionNode
{
    /**
     * @var SelectionSetNode
     */
    public SelectionSetNode $selectionSet;

    /**
     * @var NamedTypeNode|null
     */
    public ?NamedTypeNode $typeCondition = null;

    /**
     * InlineFragmentNode constructor.
     *
     * @param SelectionSetNode $selections
     */
    public function __construct(SelectionSetNode $selections)
    {
        $this->selectionSet = $selections;
    }
}
