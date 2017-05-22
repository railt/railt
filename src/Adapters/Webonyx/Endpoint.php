<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx;

use GraphQL\GraphQL;
use GraphQL\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Serafim\Railgun\Contracts\Adapters\EndpointDriverInterface;
use Serafim\Railgun\Contracts\Adapters\EndpointInterface;
use Serafim\Railgun\Contracts\Partials\FieldTypeInterface;
use Serafim\Railgun\Contracts\Partials\MutationTypeInterface;
use Serafim\Railgun\Contracts\Partials\QueryTypeInterface;
use Serafim\Railgun\Contracts\TypesRegistryInterface;
use Serafim\Railgun\Requests\RequestInterface;
use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Support\InteractWithTypesRegistry;
use Serafim\Railgun\Types\Schemas\Fields;
use Serafim\Railgun\Types\TypesRegistry as BaseTypesRegistry;

/**
 * Class Endpoint
 * @package Serafim\Railgun\Adapters\Webonyx
 */
class Endpoint implements EndpointDriverInterface
{
    use InteractWithName;
    use InteractWithTypesRegistry;

    /**
     * @var BuilderInterface|null
     */
    private $builder;

    /**
     * @var array|FieldTypeInterface[]
     */
    private $queries = [];

    /**
     * @var array|FieldTypeInterface[]
     */
    private $mutations = [];

    /**
     * @return bool
     */
    public static function isSupportedBy(): bool
    {
        return class_exists(GraphQL::class);
    }

    /**
     * Responder constructor.
     * @param string $name
     * @param TypesRegistryInterface $registry
     */
    public function __construct(string $name, TypesRegistryInterface $registry)
    {
        assert(static::isSupportedBy(), '"webonyx/graphql-php" package required');

        $this->name = $name;
        $this->registry = $registry;
    }

    /**
     * @param string $name
     * @param QueryTypeInterface $query
     * @return EndpointInterface|Endpoint
     */
    public function query(string $name, QueryTypeInterface $query): EndpointInterface
    {
        $this->queries[$name] = $query;

        return $this;
    }

    /**
     * @param string $name
     * @param MutationTypeInterface $mutation
     * @return EndpointInterface|Endpoint
     */
    public function mutation(string $name, MutationTypeInterface $mutation): EndpointInterface
    {
        $this->mutations[$name] = $mutation;

        return $this;
    }

    /**
     * @param RequestInterface $request
     * @param null $context
     * @return array
     * @throws \GraphQL\Error\InvariantViolation
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function request(RequestInterface $request, $context = null): array
    {
        $response = GraphQL::execute(...$this->buildRequestArgs($request, $context));

        if (! is_array($response)) {
            throw new \RuntimeException('Can not resolve non array response');
        }

        return $response;
    }

    /**
     * @param RequestInterface $request
     * @param null $context
     * @return array
     * @throws \InvalidArgumentException
     */
    private function buildRequestArgs(RequestInterface $request, $context = null): array
    {
        return [
            $this->buildSchema(),
            $request->getQuery(),
            null,
            $context,
            $request->getVariables(),
            $request->getOperation(),
        ];
    }

    /**
     * @return Schema
     * @throws \InvalidArgumentException
     */
    private function buildSchema(): Schema
    {
        $schema = ['query' => $this->buildQueryObject()];

        if (count($this->mutations)) {
            $schema['mutation'] = $this->buildMutationObject();
        }

        $schema['types'] = iterator_to_array($this->getTypes());

        return new Schema($schema);
    }

    /**
     * @return ObjectType
     * @throws \InvalidArgumentException
     */
    private function buildQueryObject(): ObjectType
    {
        return $this->buildRootObject('Query', 'query', $this->queries);
    }

    /**
     * @param string $nameSuffix
     * @param string $descriptionSuffix
     * @param array $items
     * @return ObjectType
     * @throws \InvalidArgumentException
     */
    private function buildRootObject(string $nameSuffix, string $descriptionSuffix, array $items): ObjectType
    {
        return new ObjectType([
            'name'        => $this->formatName($this->getName()) . $nameSuffix,
            'description' => $this->formatDescription($descriptionSuffix),
            'fields'      => $this->getBuilder()->getPartialsBuilder()->makeIterable($items),
        ]);
    }

    /**
     * @return BuilderInterface
     * @throws \InvalidArgumentException
     */
    public function getBuilder(): BuilderInterface
    {
        if ($this->builder === null) {
            $this->builder = new Builder($this->getRegistry());
        }

        return $this->builder;
    }

    /**
     * @return ObjectType
     * @throws \InvalidArgumentException
     */
    private function buildMutationObject(): ObjectType
    {
        return $this->buildRootObject('Mutation', 'mutation', $this->mutations);
    }

    /**
     * @return \Traversable
     */
    private function getTypes(): \Traversable
    {
        yield from $this->builder->getTypes();
    }
}
