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
 * Class TypeSystemDefinitionNode
 *
 * <code>
 *  export type TypeSystemDefinitionNode =
 *      | SchemaDefinitionNode
 *      | TypeDefinitionNode
 *      | DirectiveDefinitionNode
 *      ;
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L413
 *
 * @see https://github.com/graphql/graphql-js/blob/3a71d3e8a66c83241469e1a7860bc51351944017/src/language/ast.js#L413
 */
abstract class TypeSystemDefinitionNode extends DefinitionNode
{
}
