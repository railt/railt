<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast\TypeSystem\Definition;

use Railt\Parser\Ast\TypeSystem\TypeDefinitionNode;
use Railt\Parser\Ast\Generic\InputValueDefinitionCollection;

/**
 * Class InputObjectTypeDefinitionNode
 *
 * <code>
 *  export type InputObjectTypeDefinitionNode = {
 *      +kind: 'InputObjectTypeDefinition',
 *      +loc?: Location,
 *      +description?: StringValueNode,
 *      +name: NameNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +fields?: $ReadOnlyArray<InputValueDefinitionNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L525
 */
class InputObjectTypeDefinitionNode extends TypeDefinitionNode
{
    /**
     * @var InputValueDefinitionCollection|null
     */
    public ?InputValueDefinitionCollection $fields = null;
}
