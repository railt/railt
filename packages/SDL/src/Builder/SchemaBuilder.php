<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Railt\SDL\Ast\Definition\OperationTypeDefinitionNode;
use Railt\SDL\Ast\Definition\SchemaDefinitionNode;
use Railt\TypeSystem\Schema;

/**
 * @property SchemaDefinitionNode $ast
 */
class SchemaBuilder extends TypeBuilder
{
    /**
     * @return SchemaInterface|DefinitionInterface
     */
    public function build(): SchemaInterface
    {
        $schema = new Schema([
            'typeMap'    => $this->dictionary->getTypes(),
            'directives' => $this->dictionary->getDirectives(),
        ]);

        /** @var OperationTypeDefinitionNode $operation */
        foreach ($this->ast->operationTypes as $operation) {
            $type = $this->fetch($operation->type->name->value);

            switch ($operation->operation) {
                case 'query':
                    $schema->setQueryType($type);
                    break;

                case 'mutation':
                    $schema->setMutationType($type);
                    break;

                case 'subscription':
                    $schema->setSubscriptionType($type);
                    break;
            }
        }

        return $schema;
    }
}
