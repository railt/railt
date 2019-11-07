<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\SDL\Ast\Name\IdentifierNode;
use Railt\SDL\Ast\Value\StringValueNode;
use Railt\SDL\Ast\Generic\DirectiveCollection;
use Railt\SDL\Ast\Generic\OperationTypeDefinitionCollection;

/**
 * Class SchemaDefinitionNode
 *
 * <code>
 *  export interface SchemaDefinitionNode {
 *      readonly kind: 'SchemaDefinition';
 *      readonly loc?: Location;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly operationTypes: ReadonlyArray<OperationTypeDefinitionNode>;
 *  }
 * </code>
 *
 * Railt Extras:
 *
 * <code>
 *  export interface RailtSchemaDefinitionNode {
 *      readonly name?: IdentifierNode;
 *  }
 * </code>
 */
class SchemaDefinitionNode extends TypeSystemDefinitionNode
{
    /**
     * @var IdentifierNode|null
     */
    public ?IdentifierNode $name = null;

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
