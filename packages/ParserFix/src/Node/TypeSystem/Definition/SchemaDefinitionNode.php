<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\TypeSystem\Definition;

use Railt\Parser\Node\Value\StringValueNode;
use Railt\Parser\Node\Generic\DirectiveCollection;
use Railt\Parser\Node\TypeSystem\TypeSystemDefinitionNode;
use Railt\Parser\Node\Generic\OperationTypeDefinitionCollection;

/**
 * Class SchemaDefinitionNode
 *
 * <code>
 *  export type SchemaDefinitionNode = {
 *      +kind: 'SchemaDefinition',
 *      +loc?: Location,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +operationTypes: $ReadOnlyArray<OperationTypeDefinitionNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L418
 */
class SchemaDefinitionNode extends TypeSystemDefinitionNode
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
     * @var OperationTypeDefinitionCollection
     */
    public OperationTypeDefinitionCollection $operationTypes;

    /**
     * SchemaDefinitionNode constructor.
     *
     * @param OperationTypeDefinitionCollection $operations
     */
    public function __construct(OperationTypeDefinitionCollection $operations)
    {
        $this->operationTypes = $operations;
    }
}
