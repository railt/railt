<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Providers\Laravel;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Requests\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Serafim\Railgun\Requests\RequestInterface;
use Serafim\Railgun\Contracts\Adapters\EndpointInterface;

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
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . self::CONFIG_PATH, self::CONFIG_NAME);

        $config = $this->app->make(Repository::class)->get(self::CONFIG_NAME);

        $this->app->singleton(RequestInterface::class, function () {
            return Factory::create($this->app->make(Request::class));
        });

        $this->app->singleton(Endpoint::class, function () use ($config) {
            $name = Arr::get($config, 'schema', 'graphql');

            $endpoint = new Endpoint($name);

            $this->loadQueries(Arr::get($config, $name . '.queries', []), $endpoint);
            $this->loadMutations(Arr::get($config, $name . '.mutations', []), $endpoint);

            return $endpoint;
        });
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
