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
use Railt\Parser\Node\Executable\SelectionNode;

/**
 * Class FragmentSpreadNode
 *
 * <code>
 *  export type FragmentSpreadNode = {
 *      +kind: 'FragmentSpread',
 *      +loc?: Location,
 *      +name: NameNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L269
 */
class FragmentSpreadNode extends SelectionNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * FragmentSpreadNode constructor.
     *
     * @param NameNode $name
     */
    public function __construct(NameNode $name)
    {
        $this->name = $name;
    }
}
