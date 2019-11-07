<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Type;

use Railt\SDL\Ast\DefinitionNode;

/**
 * Class TypeNode
 *
 * <code>
 *  export type TypeNode =
 *      | NamedTypeNode
 *      | ListTypeNode
 *      | NonNullTypeNode
 *  ;
 * </code>
 */
abstract class TypeNode extends DefinitionNode
{
}
