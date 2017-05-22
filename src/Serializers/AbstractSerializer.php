<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Serializers;

use Illuminate\Support\Collection;

/**
 * Class AbstractSerializer
 * @package App\GraphQL\Serializers
 */
abstract class AbstractSerializer implements SerializerInterface
{
    /**
     * @return SerializerInterface
     */
    public static function instance(): SerializerInterface
    {
        return new static();
    }

    /**
     * @param iterable|array|\Traversable $items
     * @return array
     */
    public static function items(iterable $items): array
    {
        if ($items instanceof Collection) {
            return static::collection($items)->toArray();
        }

        if ($items instanceof \Traversable) {
            $items = iterator_to_array($items);
        }

        return array_map(self::mapper(), $items);
    }

    /**
     * @param Collection|object[]|null $collection
     * @return Collection
     */
    public static function collection(?Collection $collection): Collection
    {
        if ($collection === null) {
            return new Collection();
        }

        /** @var SerializerInterface $serializer */
        $serializer = static::instance();

        return $collection->map(self::mapper());
    }

    /**
     * @return \Closure
     */
    public static function mapper(): \Closure
    {
        /** @var SerializerInterface $serializer */
        $serializer = static::instance();

        return function ($object) use ($serializer): array {
            return $serializer->toArray($object);
        };
    }

    /**
     * @param object $object
     * @return array
     */
    public static function item($object): array
    {
        if ($object === null) {
            return [];
        }

        return static::instance()->toArray($object);
    }
}
