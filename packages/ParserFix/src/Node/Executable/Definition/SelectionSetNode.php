<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Executable\Definition;

use Railt\Parser\Node\DefinitionNode;
use Railt\Parser\Node\Generic\SelectionCollection;

/**
 * Class SelectionSetNode
 *
 * <code>
 *  export type SelectionSetNode = {
 *      kind: 'SelectionSet',
 *      loc?: Location,
 *      selections: $ReadOnlyArray<SelectionNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L239
 */
class SelectionSetNode extends DefinitionNode
{
    /**
     * @var SelectionCollection
     */
    public SelectionCollection $selections;

    /**
     * SelectionSetNode constructor.
     *
     * @param SelectionCollection $selections
     */
    public function __construct(SelectionCollection $selections)
    {
        $this->selections = $selections;
    }
}
