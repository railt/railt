<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Type;

use Railt\SDL\Ast\Name\IdentifierNode;

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
class NamedTypeNode extends TypeNode
{
    /**
     * @var IdentifierNode
     */
    public IdentifierNode $name;

    /**
     * NamedType constructor.
     *
     * @param IdentifierNode $name
     */
    public function __construct(IdentifierNode $name)
    {
        $this->name = $name;
    }
}
