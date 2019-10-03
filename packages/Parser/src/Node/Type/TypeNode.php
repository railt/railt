<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Type;

use Railt\Parser\Node\Node;
use Railt\Parser\Node\DefinitionNode;

/**
 * Class TypeHint
 *
 * <code>
 *  export type TypeNode =
 *      | NamedTypeNode
 *      | ListTypeNode
 *      | NonNullTypeNode
 *      ;
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L388
 */
abstract class TypeNode extends DefinitionNode
{
}
