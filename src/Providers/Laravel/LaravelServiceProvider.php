<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Providers\Laravel;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Serafim\Railgun\Contracts\Adapters\EndpointInterface;
use Serafim\Railgun\Contracts\TypesRegistryInterface;
use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Http\Request;
use Serafim\Railgun\Http\RequestInterface;
use Serafim\Railgun\Types\TypesRegistry;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Class LaravelServiceProvider
 * @package Serafim\Railgun\Providers\Laravel
 */
class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Config name
     */
    private const CONFIG_NAME = 'railgun';

    /**
     * Default config path
     */
    private const CONFIG_PATH = '/config.php';

    /**
     * Default events prefix
     */
    private const EVENT_PREFIX = 'railgun.';

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadConfigs();
    }

    /**
     * @return void
     */
    private function loadConfigs(): void
    {
        $this->publishes([__DIR__ . self::CONFIG_PATH => $this->publicConfig()], 'config');
    }

    /**
     * @return string
     */
    private function publicConfig(): string
    {
        if (function_exists('config_path')) {
            return config_path(self::CONFIG_NAME . '.php');
        }

        return base_path('config/' . self::CONFIG_NAME . '.php');
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     * @throws \DomainException
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . self::CONFIG_PATH, self::CONFIG_NAME);

        $config = $this->app->make(Repository::class)->get(self::CONFIG_NAME);

        $this->registerRequests();
        $this->registerEndpoint($config);
        $this->registerTypesRepository();
    }

    /**
     * @return void
     */
    private function registerRequests(): void
    {
        $this->app->singleton(RequestInterface::class, function () {
            return Request::create($this->app->make(IlluminateRequest::class));
        });
    }

    /**
     * @param array $config
     * @return void
     * @throws \InvalidArgumentException
     * @throws \DomainException
     */
    private function registerEndpoint(array $config): void
    {
        $this->app->singleton(EndpointInterface::class, function () use ($config) {
            $name = Arr::get($config, 'schema', 'graphql');

            $endpoint = new Endpoint($name, $this->app->make(TypesRegistryInterface::class));

            $this->loadQueries(Arr::get($config, $name . '.queries', []), $endpoint);
            $this->loadMutations(Arr::get($config, $name . '.mutations', []), $endpoint);

            return $endpoint;
        });

        $this->app->alias(EndpointInterface::class, Endpoint::class);
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    private function registerTypesRepository(): void
    {
        $types = function (string $type) {
            $this->fire('type:creating', $type);

            $instance = $this->app->make($type);

            $this->fire('type:created', $type, $instance);

            return $instance;
        };

        $schemas = function (string $schema) {
            $this->fire('schema:creating', $schema);

            $instance = $this->app->make($schema);

            $this->fire('schema:created', $schema, $instance);

            return $instance;
        };

        $this->app->singleton(TypesRegistryInterface::class, function () use ($types, $schemas) {
            return new TypesRegistry($types, $schemas);
        });
    }

    /**
     * @param string $event
     * @param array $payload
     */
    private function fire(string $event, ...$payload): void
    {
        if ($this->dispatcher === null) {
            $this->dispatcher = $this->app->make(Dispatcher::class);
        }

        $this->dispatcher->push(self::EVENT_PREFIX . $event, $payload);
    }

    /**
     * @param array $queries
     * @param EndpointInterface $endpoint
     * @return void
     */
    private function loadQueries(array $queries = [], EndpointInterface $endpoint): void
    {
        foreach ($queries as $name => $class) {
            $endpoint->query($name, $this->app->make($class));
        }
    }

    /**
     * @param array $mutations
     * @param EndpointInterface $endpoint
     * @return void
     */
    private function loadMutations(array $mutations = [], EndpointInterface $endpoint): void
    {
        foreach ($mutations as $name => $class) {
            $endpoint->mutation($name, $this->app->make($class));
        }
    }
}
