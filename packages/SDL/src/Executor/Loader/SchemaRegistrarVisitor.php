<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Loader;

use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Document\MutableDocument;
use Railt\SDL\Exception\TypeErrorException;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\Parser\Ast\TypeSystem\Definition\SchemaDefinitionNode;

/**
 * Class SchemaRegistrarVisitor
 */
class SchemaRegistrarVisitor extends RegistrarVisitor
{
    /**
     * @var string
     */
    private const ERROR_SCHEMA_REDEFINITION = 'Schema definition must be unique';

    /**
     * @param NodeInterface $node
     * @return void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    public function leave(NodeInterface $node): void
    {
        if ($node instanceof SchemaDefinitionNode) {
            $this->registerSchemaDefinition($node);
        }
    }

    /**
     * @param SchemaDefinitionNode $schema
     * @return void
     * @throws TypeErrorException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    private function registerSchemaDefinition(SchemaDefinitionNode $schema): void
    {
        if (\count($this->document->schemas()) > 0) {
            throw new TypeErrorException(self::ERROR_SCHEMA_REDEFINITION, $schema);
        }

        $this->mutate(fn (MutableDocument $document) => $document->withSchema($schema));
    }
}
