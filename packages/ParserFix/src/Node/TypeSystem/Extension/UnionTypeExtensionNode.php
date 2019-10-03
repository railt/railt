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
use Railt\Parser\Node\Generic\TypeDefinitionCollection;

/**
 * Class UnionTypeExtensionNode
 *
 * <code>
 *  export type UnionTypeExtensionNode = {
 *      +kind: 'UnionTypeExtension',
 *      +loc?: Location,
 *      +name: NameNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +types?: $ReadOnlyArray<NamedTypeNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L597
 */
class UnionTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var TypeDefinitionCollection|null
     */
    public ?TypeDefinitionCollection $types = null;
}
