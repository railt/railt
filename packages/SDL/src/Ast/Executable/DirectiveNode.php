<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Executable;

use Railt\SDL\Ast\Name\IdentifierNode;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Generic\ArgumentCollection;

/**
 * Class DirectiveNode
 *
 * <code>
 *  export interface DirectiveNode {
 *      readonly kind: 'Directive';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly arguments?: ReadonlyArray<ArgumentNode>;
 *  }
 * </code>
 */
class DirectiveNode extends DefinitionNode
{
    /**
     * @var IdentifierNode
     */
    public IdentifierNode $name;

    /**
     * @var ArgumentCollection|null
     */
    public ?ArgumentCollection $arguments = null;

    /**
     * DirectiveNode constructor.
     *
     * @param IdentifierNode $name
     */
    public function __construct(IdentifierNode $name)
    {
        $this->name = $name;
    }
}
