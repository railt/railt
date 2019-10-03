<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\TypeSystem\Definition;

use Railt\Parser\Node\TypeSystem\TypeDefinitionNode;

/**
 * Class ScalarTypeDefinitionNode
 *
 * <code>
 *  export type ScalarTypeDefinitionNode = {
 *      +kind: 'ScalarTypeDefinition',
 *      +loc?: Location,
 *      +description?: StringValueNode,
 *      +name: NameNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L444
 */
class ScalarTypeDefinitionNode extends TypeDefinitionNode
{
}
