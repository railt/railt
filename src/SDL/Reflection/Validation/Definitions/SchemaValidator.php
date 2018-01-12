<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Validation\Definitions;

use Railt\SDL\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;

/**
 * Class SchemaValidator
 */
class SchemaValidator extends BaseDefinitionValidator
{
    /**
     * @param Definition $definition
     * @return bool
     */
    public function match(Definition $definition): bool
    {
        return $definition instanceof SchemaDefinition;
    }

    /**
     * @param Definition|SchemaDefinition $definition
     * @return void
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    public function validate(Definition $definition): void
    {
        $this->checkQueryExistence($definition);
        $this->checkSchemaActionCompatibilities($definition);
    }

    /**
     * @param SchemaDefinition $schema
     * @return void
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    private function checkQueryExistence(SchemaDefinition $schema): void
    {
        try {
            $schema->getQuery();
        } catch (\TypeError $typo) {
            $error = \sprintf('The %s must contain an Object type reference to the "query" field', $schema);
            throw new TypeConflictException($error, $this->getCallStack(), $typo);
        }
    }

    /**
     * @param SchemaDefinition $schema
     * @return void
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    private function checkSchemaActionCompatibilities(SchemaDefinition $schema): void
    {
        $this->checkSchemaField($schema, 'query', 'getQuery');
        $this->checkSchemaField($schema, 'mutation', 'getMutation');
        $this->checkSchemaField($schema, 'subscription', 'getSubscription');
    }

    /**
     * @param SchemaDefinition $schema
     * @param string $field
     * @param string $action
     * @return void
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    private function checkSchemaField(SchemaDefinition $schema, string $field, string $action): void
    {
        try {
            $definition = $schema->$action();
            $this->checkSchemaFieldCompatibility($field, $definition);
        } catch (\TypeError $typo) {
            $error = \vsprintf('The %s contain incompatible type for field %s', [
                $schema,
                $field,
            ]);

            throw new TypeConflictException($error, $this->getCallStack(), $typo);
        }
    }

    /**
     * @param string $field
     * @param null|Definition $definition
     * @return void
     */
    private function checkSchemaFieldCompatibility(string $field, ?Definition $definition): void
    {
        /**
         * Action is not defined. All OK. The presence
         * of the "query" field is checked above.
         */
        if ($definition === null) {
            return;
        }

        /**
         * The schema action should be compatible with Object type only.
         */
        if ($definition instanceof ObjectDefinition) {
            return;
        }

        $error = \vsprintf('Schema field %s contain incompatible type %s', [
            $field,
            $definition,
        ]);

        throw new TypeConflictException($error, $this->getCallStack());
    }
}
