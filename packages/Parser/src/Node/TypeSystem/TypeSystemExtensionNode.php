<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\TypeSystem;

use Railt\Parser\Node\DefinitionNode;

/**
 * Class TypeSystemExtensionNode
 *
 * <code>
 *  export type TypeSystemExtensionNode =
 *      | SchemaExtensionNode
 *      | TypeExtensionNode
 *      ;
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L550
 */
abstract class TypeSystemExtensionNode extends DefinitionNode
{
}
