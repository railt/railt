<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Executable;

use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\NamedDirectiveNode;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;

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
     * @var NamedDirectiveNode
     */
    public NamedDirectiveNode $name;

    /**
     * @var ArgumentNode[]
     */
    public array $arguments = [];

    /**
     * DirectiveNode constructor.
     *
     * @param NamedDirectiveNode $name
     */
    public function __construct(NamedDirectiveNode $name)
    {
        $this->name = $name;
    }

    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        $directive = new static($children[0]);

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof ArgumentNode:
                    $directive->arguments[] = $child;
                    break;
            }
        }

        return $directive;
    }
}
