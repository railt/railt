<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Ast\Executable\Definition;

use Railt\Ast\NameNode;
use Railt\Ast\DefinitionNode;
use Railt\Ast\Generic\ArgumentCollection;

/**
 * Class DirectiveNode
 *
 * <code>
 *  export type DirectiveNode = {
 *      +kind: 'Directive',
 *      +loc?: Location,
 *      +name: NameNode,
 *      +arguments?: $ReadOnlyArray<ArgumentNode>,
 *      ...
 *  };
 * </code>
 */
class DirectiveNode extends DefinitionNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * @var ArgumentCollection|null
     */
    public ?ArgumentCollection $arguments = null;

    /**
     * DirectiveNode constructor.
     *
     * @param NameNode $name
     */
    public function __construct(NameNode $name)
    {
        $this->name = $name;
    }
}
