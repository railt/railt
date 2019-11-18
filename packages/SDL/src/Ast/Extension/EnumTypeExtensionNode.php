<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Extension;

use Railt\SDL\Ast\Generic\EnumValueDefinitionCollection;

/**
 * Class EnumTypeExtensionNode
 *
 * <code>
 *  export interface EnumTypeExtensionNode {
 *      readonly kind: 'EnumTypeExtension';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly values?: ReadonlyArray<EnumValueDefinitionNode>;
 *  }
 * </code>
 */
class EnumTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var EnumValueDefinitionCollection|null
     */
    public ?EnumValueDefinitionCollection $values = null;
}
