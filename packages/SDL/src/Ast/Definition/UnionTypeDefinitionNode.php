<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\SDL\Ast\Generic\TypeDefinitionCollection;

/**
 * Class UnionTypeDefinitionNode
 *
 * <code>
 *  export interface UnionTypeDefinitionNode {
 *      readonly kind: 'UnionTypeDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly types?: ReadonlyArray<NamedTypeNode>;
 *  }
 * </code>
 */
class UnionTypeDefinitionNode extends TypeDefinitionNode
{
    /**
     * @var TypeDefinitionCollection|null
     */
    public ?TypeDefinitionCollection $types = null;
}
