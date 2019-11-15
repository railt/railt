<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\SDL\Ast\Generic\InputFieldDefinitionCollection;

/**
 * Class InputObjectTypeDefinitionNode
 *
 * <code>
 *  export interface InputObjectTypeDefinitionNode {
 *      readonly kind: 'InputObjectTypeDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly fields?: ReadonlyArray<InputValueDefinitionNode>;
 *  }
 * </code>
 */
class InputObjectTypeDefinitionNode extends TypeDefinitionNode
{
    /**
     * @var InputFieldDefinitionCollection|null
     */
    public ?InputFieldDefinitionCollection $fields = null;
}
