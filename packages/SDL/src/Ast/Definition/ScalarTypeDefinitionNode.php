<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

/**
 * Class ScalarTypeDefinitionNode
 *
 * <code>
 *  export interface ScalarTypeDefinitionNode {
 *      readonly kind: 'ScalarTypeDefinition';
 *      readonly loc?: Location;
 *      readonly description?: StringValueNode;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *  }
 * </code>
 */
class ScalarTypeDefinitionNode extends TypeDefinitionNode
{
}
