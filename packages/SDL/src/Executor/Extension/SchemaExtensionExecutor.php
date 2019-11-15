<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Extension;

use GraphQL\TypeSystem\Schema;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Ast\Definition\OperationTypeDefinitionNode;
use Railt\SDL\Ast\Extension\SchemaExtensionNode;

/**
 * Class SchemaExtensionExecutor
 */
class SchemaExtensionExecutor extends ExtensionExecutor
{
    /**
     * @param NodeInterface|SchemaExtensionNode $source
     * @return mixed|void|null
     */
    public function enter(NodeInterface $source)
    {
        if (! $source instanceof SchemaExtensionNode) {
            return;
        }

        /** @var Schema $target */
        $target = $this->document->getSchema();

        if (! $target instanceof Schema) {
            // TODO should throw an error
            return;
        }

        if ($source->operationTypes) {
            /** @var OperationTypeDefinitionNode $operation */
            foreach ($source->operationTypes as $operation) {
                // TODO check types
                $type = $this->fetch($operation->type->name->value);

                switch ($operation->operation) {
                    case 'query':
                        $target = $target->withQueryType($type);
                        break;

                    case 'mutation':
                        $target = $target->withMutationType($type);
                        break;

                    case 'subscription':
                        $target = $target->withSubscriptionType($type);
                        break;
                }
            }
        }

        $this->document->setSchema($target);
    }
}
