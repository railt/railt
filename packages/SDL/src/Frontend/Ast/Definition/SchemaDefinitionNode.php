<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition;

use Railt\SDL\Frontend\Ast\Description;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\StringValue;

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
     * @var StringValue|null
     */
    public ?StringValue $description = null;

    /**
     * @var DirectiveNode[]
     */
    public array $directives = [];

    /**
     * @var OperationTypeDefinitionNode[]
     */
    public array $operationTypes = [];

    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        $schema = new static();

        foreach ($children as $child) {
            switch (true) {
                case $child instanceof Description:
                    $schema->description = $child->value;
                    break;

                case $child instanceof DirectiveNode:
                    $schema->directives[] = $child;
                    break;

                case $child instanceof OperationTypeDefinitionNode:
                    $schema->operationTypes[] = $child;
                    break;
            }
        }

        return $schema;
    }
}
