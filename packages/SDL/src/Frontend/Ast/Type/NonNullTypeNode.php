<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Type;

use Railt\SDL\Frontend\Ast\Node;

/**
 * Class NonNullTypeNode
 *
 * <code>
 *  export interface NonNullTypeNode {
 *      readonly kind: 'NonNullType';
 *      readonly loc?: Location;
 *      readonly type: NamedTypeNode | ListTypeNode;
 *  }
 * </code>
 */
class NonNullTypeNode extends TypeNode
{
    /**
     * @var TypeNode
     */
    public TypeNode $type;

    /**
     * NonNullTypeNode constructor.
     *
     * @param TypeNode $type
     */
    public function __construct(TypeNode $type)
    {
        \assert(! $type instanceof self);

        $this->type = $type;
    }

    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        return new static($children[0]);
    }
}
