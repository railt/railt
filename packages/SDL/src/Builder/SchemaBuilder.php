<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\TypeSystem\Schema;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Railt\SDL\Ast\Definition\SchemaDefinitionNode;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Ast\Definition\OperationTypeDefinitionNode;

/**
 * @property SchemaDefinitionNode $ast
 */
class SchemaBuilder extends TypeBuilder
{
    /**
     * @return SchemaInterface|DefinitionInterface
     * @throws \RuntimeException
     */
    public function build(): SchemaInterface
    {
        $schema = new Schema([
            'typeMap'    => $this->dictionary->typeMap,
            'directives' => $this->dictionary->directives,
        ]);

        /** @var OperationTypeDefinitionNode $operation */
        foreach ($this->ast->operationTypes as $operation) {
            $type = $this->getType($operation->type->name->value);

            switch ($operation->operation) {
                case 'query':
                    $schema = $schema->withQueryType($type);
                    break;

                case 'mutation':
                    $schema = $schema->withMutationType($type);
                    break;

                case 'subscription':
                    $schema = $schema->withSubscriptionType($type);
                    break;
            }
        }

        return $schema;
    }
}
