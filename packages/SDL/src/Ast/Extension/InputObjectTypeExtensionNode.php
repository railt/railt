<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Extension;

use Railt\SDL\Ast\Generic\InputValueDefinitionCollection;

/**
 * Class InputObjectTypeExtensionNode
 *
 * <code>
 *  export interface InputObjectTypeExtensionNode {
 *      readonly kind: 'InputObjectTypeExtension';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly fields?: ReadonlyArray<InputValueDefinitionNode>;
 *  }
 * </code>
 */
class InputObjectTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var InputValueDefinitionCollection|null
     */
    public ?InputValueDefinitionCollection $fields = null;
}
