<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition\Type;

use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;

/**
 * Class InterfaceImplementsNode
 */
class ImplementedInterfaceNode extends Node
{
    /**
     * @var NamedTypeNode
     */
    public NamedTypeNode $interface;

    /**
     * ImplementedInterface constructor.
     *
     * @param NamedTypeNode $interface
     */
    public function __construct(NamedTypeNode $interface)
    {
        $this->interface = $interface;
    }

    /**
     * @param array|NamedTypeNode[] $children
     * @return array|static[]
     */
    public static function create(array $children): array
    {
        return \array_map(fn (NamedTypeNode $type): self => new static($type), $children);
    }
}
