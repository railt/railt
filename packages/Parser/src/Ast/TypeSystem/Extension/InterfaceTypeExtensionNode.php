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

/**
 * Class InterfaceTypeExtensionNode
 *
 * <code>
 *  export type InterfaceTypeExtensionNode = {
 *      +kind: 'InterfaceTypeExtension',
 *      +loc?: Location,
 *      +name: NameNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +fields?: $ReadOnlyArray<FieldDefinitionNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L588
 */
class InterfaceTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @var FieldDefinitionCollection|null
     */
    public ?FieldDefinitionCollection $fields = null;
}
