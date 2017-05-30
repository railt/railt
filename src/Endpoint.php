<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun;

use Serafim\Railgun\Adapters\AdapterInterface;
use Serafim\Railgun\Adapters\Webonyx\WebonyxAdapter;
use Serafim\Railgun\Http\RequestInterface;
use Serafim\Railgun\Http\ResponderInterface;
use Serafim\Railgun\Schema\Registry as Schemas;
use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Support\NameableInterface;
use Serafim\Railgun\Types\Registry as Types;

/**
 * Class Endpoint
 * @package Serafim\Railgun
 */
class Endpoint implements
    NameableInterface,
    ResponderInterface
{
    use InteractWithName;

    private const EVENT_ON_CREATE_SCHEMA = 'onSchemaCreate';
    private const EVENT_ON_CREATE_TYPE = 'onTypeCreate';

    /**
     * @var Types
     */
    private $types;

    /**
     * @var Schemas
     */
    private $schemas;

    /**
     * @var array
     */
    private $events = [
        self::EVENT_ON_CREATE_SCHEMA => [],
        self::EVENT_ON_CREATE_TYPE   => [],
    ];

    /**
     * @var array|AdapterInterface[]
     */
    private $adapters = [
        WebonyxAdapter::class,
    ];

    /**
     * @var array|QueryInterface[]
     */
    private $queries = [];

    /**
     * @var array|MutationInterface[]
     */
    private $mutations = [];

    /**
     * Endpoint constructor.
     * @param string $name
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name = 'Root')
    {
        $this->types = new Types(function (string $type) {
            return $this->fire(self::EVENT_ON_CREATE_TYPE, $type);
        });

        $this->schemas = new Schemas(function (string $schema) {
            return $this->fire(self::EVENT_ON_CREATE_SCHEMA, $schema);
        });
    }

    /**
     * @param string $event
     * @param string $class
     * @return mixed
     */
    private function fire(string $event, string $class)
    {
        $exists = count($this->events[$event] ?? []) > 0;

        if ($exists) {
            /** @var \Closure $callback */
            foreach ((array)$this->events[$event] as $callback) {
                $result = $callback($class);

                if ($result !== null) {
                    $class = $result;
                }
            }

            return $class;
        }

        return new $class();
    }

    /**
     * @param string $class
     * @return Endpoint
     */
    public function addAdapter(string $class): Endpoint
    {
        $this->adapters[] = $class;

        return $this;
    }

    /**
     * @param string|QueryInterface $query
     * @param null|string $name
     * @return Endpoint
     */
    public function addQuery($query, ?string $name = null): Endpoint
    {
        /** @var QueryInterface $instance */
        $instance = is_string($query) ? new $query : $query;

        $this->queries[$name ?? $instance->getName()] = $instance;

        return $this;
    }

    /**
     * @param string|MutationInterface $mutation
     * @param null|string $name
     * @return Endpoint
     */
    public function addMutation($mutation, ?string $name = null): Endpoint
    {
        /** @var MutationInterface $instance */
        $instance = is_string($mutation) ? new $mutation : $mutation;

        $this->mutations[$name ?? $instance->getName()] = $instance;

        return $this;
    }

    /**
     * @param \Closure $then
     * @return Endpoint
     */
    public function onCreateType(\Closure $then): Endpoint
    {
        $this->events[self::EVENT_ON_CREATE_TYPE][] = $then;

        return $this;
    }

    /**
     * @param \Closure $then
     * @return Endpoint
     */
    public function onCreateSchema(\Closure $then): Endpoint
    {
        $this->events[self::EVENT_ON_CREATE_SCHEMA][] = $then;

        return $this;
    }

    /**
     * @return array|QueryInterface[]
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * @return array|MutationInterface[]
     */
    public function getMutations(): array
    {
        return $this->mutations;
    }

    /**
     * @return Types
     */
    public function getTypes(): Types
    {
        return $this->types;
    }

    /**
     * @return Schemas
     */
    public function getSchemas(): Schemas
    {
        return $this->schemas;
    }

    /**
     * @param RequestInterface $request
     * @return array
     * @throws \RuntimeException
     */
    public function request(RequestInterface $request): array
    {
        return $this->getAdapter()->request($request);
    }

    /**
     * @return AdapterInterface
     * @throws \RuntimeException
     */
    private function getAdapter(): AdapterInterface
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter::isSupportedBy()) {
                return new $adapter($this);
            }
        }

        throw new \RuntimeException('Can not find any supported adapters.');
    }
}
