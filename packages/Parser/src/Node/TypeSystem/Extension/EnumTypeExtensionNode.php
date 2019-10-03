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
use Railt\Parser\Node\Generic\EnumValueDefinitionCollection;

/**
 * Class EnumTypeExtensionNode
 *
 * <code>
 *  export type EnumTypeExtensionNode = {
 *      +kind: 'EnumTypeExtension',
 *      +loc?: Location,
 *      +name: NameNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +values?: $ReadOnlyArray<EnumValueDefinitionNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L606
 */
class EnumTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var EnumValueDefinitionCollection|null
     */
    public ?EnumValueDefinitionCollection $values = null;
}
