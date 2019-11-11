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
        $schema = new Schema();

        /** @var OperationTypeDefinitionNode $operation */
        foreach ($this->ast->operationTypes as $operation) {
            $type = $this->getType($operation->type->name->value);

            switch ($operation->operation) {
                case 'query':
                    $schema->query = $type;
                    break;

                case 'mutation':
                    $schema->mutation = $type;
                    break;

                case 'subscription':
                    $schema->subscription = $type;
                    break;
            }
        }

        $schema->typeMap = $this->dictionary->typeMap->toArray();
        $schema->directives = $this->dictionary->directives->toArray();

        return $schema;
    }
}
