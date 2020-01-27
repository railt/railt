<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Type;

use Railt\SDL\Frontend\Ast\Identifier;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class NamedTypeNode
 *
 * <code>
 *  export interface NamedTypeNode {
 *      readonly kind: 'NamedType';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *  }
 * </code>
 */
class NamedDirectiveNode extends TypeNode
{
    /**
     * @var Identifier
     */
    public Identifier $name;

    /**
     * NamedType constructor.
     *
     * @param Identifier $name
     */
    public function __construct(Identifier $name)
    {
        $this->name = $name;
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
