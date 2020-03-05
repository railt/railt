<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Connection;

use Railt\Http\Identifiable;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Reflection\Dictionary;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ConnectionEvent
 */
abstract class ConnectionEvent extends Event implements ConnectionEventInterface
{
    /**
     * @var Identifiable
     */
    private $connection;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var SchemaDefinition
     */
    private $schema;

    /**
     * ConnectionEvent constructor.
     *
     * @param Identifiable $connection
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     */
    public function __construct(Identifiable $connection, Dictionary $dictionary, SchemaDefinition $schema)
    {
        $this->connection = $connection;
        $this->dictionary = $dictionary;
        $this->schema = $schema;
    }

    /**
     * @return Identifiable
     */
    public function getConnection(): Identifiable
    {
        return $this->connection;
    }

    /**
     * @param Identifiable $connection
     * @return ConnectionEvent|$this
     */
    public function withConnection(Identifiable $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'connection' => $this->getId(),
            'schema'     => $this->getSchema()->getFileName(),
            'dictionary' => \count($this->getDictionary()->all()),
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->connection->getId();
    }

    /**
     * @return SchemaDefinition
     */
    public function getSchema(): SchemaDefinition
    {
        return $this->schema;
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }
}
