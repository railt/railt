<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Youshido;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Youshido\GraphQL\Schema\Schema;
use Railt\Adapters\AdapterInterface;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;
use Youshido\GraphQL\Type\TypeInterface;

/**
 * Class SchemaBuilder
 */
class SchemaBuilder
{
    private const FIELD_NAME_QUERY = 'query';
    private const FIELD_NAME_MUTATION = 'mutation';

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var SchemaDefinition
     */
    private $schema;

    /**
     * SchemaBuilder constructor.
     * @param AdapterInterface $adapter
     * @param SchemaDefinition $schema
     */
    public function __construct(AdapterInterface $adapter, SchemaDefinition $schema)
    {
        $this->adapter = $adapter;
        $this->schema = $schema;
    }

    /**
     * @return Schema
     */
    public function build(): Schema
    {
        $data = $this->query();

        if ($this->schema->hasMutation()) {
            $data = $this->mutation($data);
        }

        return new Schema($data);
    }

    /**
     * @param array $data
     * @return array
     */
    private function mutation(array $data = []): array
    {
        return \array_merge($data, [
            self::FIELD_NAME_MUTATION => $this->resolve($this->schema->getMutation())
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    private function query(array $data = []): array
    {
        return \array_merge($data, [
            self::FIELD_NAME_QUERY => $this->resolve($this->schema->getQuery())
        ]);
    }

    /**
     * @param TypeDefinition $type
     * @return TypeInterface
     */
    private function resolve(TypeDefinition $type): TypeInterface
    {
        return $this->adapter->get($type);
    }
}
