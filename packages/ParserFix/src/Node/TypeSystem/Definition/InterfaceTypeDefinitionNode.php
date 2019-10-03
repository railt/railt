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
use Railt\Parser\Node\Generic\FieldDefinitionCollection;

/**
 * Class InterfaceTypeDefinitionNode
 *
 * <code>
 *  export type InterfaceTypeDefinitionNode = {
 *      +kind: 'InterfaceTypeDefinition',
 *      +loc?: Location,
 *      +description?: StringValueNode,
 *      +name: NameNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +fields?: $ReadOnlyArray<FieldDefinitionNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L486
 */
class InterfaceTypeDefinitionNode extends TypeDefinitionNode
{
    /**
     * @var FieldDefinitionCollection|null
     */
    public ?FieldDefinitionCollection $fields = null;
}
