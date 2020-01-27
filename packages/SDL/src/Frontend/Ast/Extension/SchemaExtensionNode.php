<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Extension;

use Railt\SDL\Frontend\Ast\Definition\OperationTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class SchemaExtensionNode
 *
 * <code>
 *  export type SchemaExtensionNode = {
 *      readonly kind: 'SchemaExtension';
 *      readonly loc?: Location;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly operationTypes?: ReadonlyArray<OperationTypeDefinitionNode>;
 *  }
 * </code>
 */
class SchemaExtensionNode extends TypeSystemExtensionNode
{
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
