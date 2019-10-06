<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Ast\TypeSystem\Extension;

use Railt\Ast\TypeSystem\TypeExtensionNode;
use Railt\Ast\Generic\InputValueDefinitionCollection;

/**
 * Class InputObjectTypeExtensionNode
 *
 * <code>
 *  export type InputObjectTypeExtensionNode = {
 *      +kind: 'InputObjectTypeExtension',
 *      +loc?: Location,
 *      +name: NameNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +fields?: $ReadOnlyArray<InputValueDefinitionNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L615
 */
class InputObjectTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var InputValueDefinitionCollection|null
     */
    public ?InputValueDefinitionCollection $fields = null;
}
