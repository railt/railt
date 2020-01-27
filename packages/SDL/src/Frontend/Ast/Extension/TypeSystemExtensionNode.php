<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Extension;

use Railt\SDL\Frontend\Ast\DefinitionNode;

/**
 * Class TypeSystemExtensionNode
 *
 * <code>
 *  export type TypeSystemExtensionNode =
 *      | SchemaExtensionNode
 *      | TypeExtensionNode
 *  ;
 * </code>
 */
abstract class TypeSystemExtensionNode extends DefinitionNode
{
}
