<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection;

use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Railt\Introspection\Builder\Registry;
use Railt\Introspection\Exception\IntrospectionException;
use Railt\Introspection\Origin\OriginInterface;
use Railt\TypeSystem\Schema;

/**
 * Class Client
 */
final class Client
{
    /**
     * @var string
     */
    private const ERROR_INVALID_OPERATION =
        'Schema %s operation must contain a reference to Object<Any> type, ' .
        'but contains a reference to %s<%s> type';

    /**
     * @var string
     */
    private const DATA_FIELD_NAME = 'data';

    /**
     * @var CacheInterface|null
     */
    private ?CacheInterface $cache;

    /**
     * @var int|\DateInterval|null
     */
    private $ttl;

    /**
     * Client constructor.
     *
     * @param CacheInterface|null $cache A PSR-16 cache implementation.
     * @param null|int|\DateInterval $ttl Optional. The TTL value of this item. If no value is sent and
     *                                    the driver supports TTL then the library may set a default value
     *                                    for it or let the driver take care of that.
     */
    public function __construct(CacheInterface $cache = null, $ttl = null)
    {
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    /**
     * @param OriginInterface $origin
     * @return SchemaInterface
     * @throws \Throwable
     * @throws InvalidArgumentException
     */
    public function read(OriginInterface $origin): SchemaInterface
    {
        return $this->cached($origin, function (OriginInterface $origin): SchemaInterface {
            $data = $this->query($origin)[self::DATA_FIELD_NAME]['__schema'] ?? [];

            $schema = new Schema();

            $registry = new Registry($schema, $data['types'] ?? []);

            return $this->buildSchema($schema, $data, $registry);
        });
    }

    /**
     * @param OriginInterface $origin
     * @param \Closure $otherwise
     * @return SchemaInterface
     * @throws InvalidArgumentException
     */
    private function cached(OriginInterface $origin, \Closure $otherwise): SchemaInterface
    {
        if ($this->cache === null) {
            return $otherwise($origin);
        }

        if (! $this->cache->has($id = $origin->getId())) {
            $result = $otherwise($origin);

            $this->cache->set($id, $result, $this->ttl);

            return $result;
        }

        return $this->cache->get($id);
    }

    /**
     * @param OriginInterface $origin
     * @return array
     */
    private function query(OriginInterface $origin): array
    {
        return $origin->load();
    }

    /**
     * @param Schema $schema
     * @param array $data
     * @param Registry $registry
     * @return SchemaInterface
     * @throws IntrospectionException
     * @throws \Throwable
     */
    private function buildSchema(Schema $schema, array $data, Registry $registry): SchemaInterface
    {
        foreach ($registry->build() as $type) {
            $schema->addType($type);
        }

        foreach ($data['directives'] ?? [] as $directive) {
            $schema->addDirective($registry->directive($directive));
        }

        $this->resolveSchemaField('queryType', $data, $registry,
            static function (ObjectTypeInterface $type) use ($schema, $registry) {
                $schema->setQueryType($registry->reference($type->getName()));
            });

        $this->resolveSchemaField('mutationType', $data, $registry,
            static function (ObjectTypeInterface $type) use ($schema, $registry) {
                $schema->setMutationType($registry->reference($type->getName()));
            });

        $this->resolveSchemaField('subscriptionType', $data, $registry,
            static function (ObjectTypeInterface $type) use ($schema, $registry) {
                $schema->setSubscriptionType($registry->reference($type->getName()));
            });

        return $schema;
    }

    /**
     * @param string $field
     * @param array $schema
     * @param Registry $registry
     * @param \Closure $then
     * @return void
     * @throws IntrospectionException
     */
    private function resolveSchemaField(string $field, array $schema, Registry $registry, \Closure $then): void
    {
        /** @var string $name */
        if ($name = ($schema[$field]['name'] ?? null)) {
            $object = $registry->get($name);

            if (! $object instanceof ObjectTypeInterface) {
                $type = $object->jsonSerialize()['kind'] ?? 'Unknown';

                throw new IntrospectionException(
                    \sprintf(self::ERROR_INVALID_OPERATION, $field, $type, $object->getName())
                );
            }

            $then($object);
        }
    }
}
