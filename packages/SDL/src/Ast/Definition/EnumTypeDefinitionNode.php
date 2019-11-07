<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\SDL\Ast\Generic\EnumValueDefinitionCollection;

/**
 * Class EnumTypeDefinitionNode
 *
 * <code>
 *  export interface EnumTypeDefinitionNode {
 *      readonly kind: 'EnumTypeDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly values?: ReadonlyArray<EnumValueDefinitionNode>;
 *  }
 * </code>
 */
class EnumTypeDefinitionNode extends TypeDefinitionNode
{
    /**
     * @var EnumValueDefinitionCollection|null
     */
    public ?EnumValueDefinitionCollection $values = null;
}
