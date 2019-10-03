<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\TypeSystem\Extension;

use Railt\Parser\Node\TypeSystem\TypeExtensionNode;

/**
 * Class ScalarTypeExtensionNode
 *
 * <code>
 *  export type ScalarTypeExtensionNode = {
 *      +kind: 'ScalarTypeExtension',
 *      +loc?: Location,
 *      +name: NameNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L570
 */
class ScalarTypeExtensionNode extends TypeExtensionNode
{
}
