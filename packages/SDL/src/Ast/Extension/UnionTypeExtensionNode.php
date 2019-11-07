<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Extension;

use Railt\SDL\Ast\Generic\TypeDefinitionCollection;

/**
 * Class UnionTypeExtensionNode
 *
 * <code>
 *  export interface UnionTypeExtensionNode {
 *      readonly kind: 'UnionTypeExtension';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly types?: ReadonlyArray<NamedTypeNode>;
 *  }
 * </code>
 */
class UnionTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var TypeDefinitionCollection|null
     */
    public ?TypeDefinitionCollection $types = null;
}
