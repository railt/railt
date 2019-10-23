<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast\TypeSystem\Extension;

use Railt\Parser\Ast\TypeSystem\TypeExtensionNode;
use Railt\Parser\Ast\Generic\FieldDefinitionCollection;
use Railt\Parser\Ast\Generic\InterfaceTypeDefinitionCollection;

/**
 * Class ObjectTypeExtensionNode
 *
 * <code>
 *  export type ObjectTypeExtensionNode = {
 *      +kind: 'ObjectTypeExtension',
 *      +loc?: Location,
 *      +name: NameNode,
 *      +interfaces?: $ReadOnlyArray<NamedTypeNode>,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +fields?: $ReadOnlyArray<FieldDefinitionNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L578
 */
class ObjectTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var InterfaceTypeDefinitionCollection|null
     */
    public ?InterfaceTypeDefinitionCollection $interfaces = null;

    /**
     * @var FieldDefinitionCollection|null
     */
    public ?FieldDefinitionCollection $fields = null;
}
