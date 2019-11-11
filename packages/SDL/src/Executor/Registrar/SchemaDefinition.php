<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Registrar;

use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Phplrt\Visitor\Traverser;
use Railt\SDL\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Exception\TypeErrorException;

/**
 * Class SchemaDefinition
 */
class SchemaDefinition extends TypeRegistrar
{
    /**
     * @var string
     */
    private const ERROR_SCHEMA_DEFINITION_UNIQUENESS = 'Schema can only be defined once';

    /**
     * @param NodeInterface $node
     * @return int|void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    public function enter(NodeInterface $node)
    {
        if ($node instanceof SchemaDefinitionNode) {
            $this->assertUniqueness($node);

            $this->registry->schema = $node;

            //
            // Temporary optimization.
            // If there is an implementation of nested types,
            // then this code should be deleted.
            //
            return Traverser::DONT_TRAVERSE_CHILDREN;
        }
    }

    /**
     * @param SchemaDefinitionNode $type
     * @return void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    private function assertUniqueness(SchemaDefinitionNode $type): void
    {
        if ($this->registry->schema || $this->dictionary->schema) {
            throw new TypeErrorException(self::ERROR_SCHEMA_DEFINITION_UNIQUENESS, $type);
        }
    }
}
