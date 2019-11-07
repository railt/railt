<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Extension;

/**
 * Class ScalarTypeExtensionNode
 *
 * <code>
 *  export interface ScalarTypeExtensionNode {
 *      readonly kind: 'ScalarTypeExtension';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *  }
 * </code>
 */
class ScalarTypeExtensionNode extends TypeExtensionNode
{
}
