<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Type\NamedTypeNode;

/**
 * Class OperationTypeDefinitionNode
 *
 * <code>
 *  export interface OperationTypeDefinitionNode {
 *      readonly kind: 'OperationTypeDefinition';
 *      readonly loc?: Location;
 *      readonly operation: OperationTypeNode;
 *      readonly type: NamedTypeNode;
 *  }
 * </code>
 */
class OperationTypeDefinitionNode extends DefinitionNode
{
    /**
     * @var string
     */
    public string $operation;

    /**
     * @var NamedTypeNode
     */
    public NamedTypeNode $type;

    /**
     * OperationTypeDefinitionNode constructor.
     *
     * @param string $operation
     * @param NamedTypeNode $type
     */
    public function __construct(string $operation, NamedTypeNode $type)
    {
        $this->operation = $operation;
        $this->type = $type;
    }
}
