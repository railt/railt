<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Ast\TypeSystem\Extension;

use Railt\Ast\Value\StringValueNode;
use Railt\Ast\Generic\DirectiveCollection;
use Railt\Ast\TypeSystem\TypeSystemExtensionNode;
use Railt\Ast\Generic\OperationTypeDefinitionCollection;

/**
 * Class SchemaExtensionNode
 *
 * <code>
 *  export type SchemaExtensionNode = {
 *      +kind: 'SchemaExtension',
 *      +loc?: Location,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +operationTypes?: $ReadOnlyArray<OperationTypeDefinitionNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L552
 */
class SchemaExtensionNode extends TypeSystemExtensionNode
{
    /**
     * @var StringValueNode|null
     */
    public ?StringValueNode $description = null;

    /**
     * @var DirectiveCollection|null
     */
    public ?DirectiveCollection $directives = null;

    /**
     * @var OperationTypeDefinitionCollection|null
     */
    public ?OperationTypeDefinitionCollection $operationTypes = null;
}
