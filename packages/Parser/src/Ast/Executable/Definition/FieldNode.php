<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast\Executable\Definition;

use Railt\Parser\Ast\NameNode;
use Railt\Parser\Ast\Executable\SelectionNode;
use Railt\Parser\Ast\Generic\ArgumentCollection;

/**
 * Class FieldNode
 *
 * <code>
 *  export type FieldNode = {
 *      +kind: 'Field',
 *      +loc?: Location,
 *      +alias?: NameNode,
 *      +name: NameNode,
 *      +arguments?: $ReadOnlyArray<ArgumentNode>,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +selectionSet?: SelectionSetNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L248
 */
class FieldNode extends SelectionNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * @var NameNode|null
     */
    public ?NameNode $alias = null;

    /**
     * @var ArgumentCollection|null
     */
    public ?ArgumentCollection $arguments = null;

    /**
     * @var SelectionSetNode|null
     */
    public ?SelectionSetNode $selectionSet = null;

    /**
     * FieldNode constructor.
     *
     * @param NameNode $name
     * @param NameNode|null $alias
     */
    public function __construct(NameNode $name, NameNode $alias = null)
    {
        $this->name = $name;
        $this->alias = $alias;
    }
}
