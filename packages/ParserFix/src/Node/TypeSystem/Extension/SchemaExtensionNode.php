<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\TypeSystem\Extension;

use Railt\Parser\Node\Value\StringValueNode;
use Railt\Parser\Node\Generic\DirectiveCollection;
use Railt\Parser\Node\TypeSystem\TypeSystemExtensionNode;
use Railt\Parser\Node\Generic\OperationTypeDefinitionCollection;

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
