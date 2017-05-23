# Laravel 5.1+ integration

- [laravel/framework](https://github.com/laravel/framework)

## Adding a ServiceProvider

Just open `~/config/app.php` and find `providers` section. 
Then add `Serafim\Railgun\Providers\Laravel\LaravelServiceProvider` class.

```php
    'providers' => [
        // ...
        Serafim\Railgun\Providers\Laravel\LaravelServiceProvider::class,
    ]
``` 

## Controller

```php
use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Requests\RequestInterface;

class MyController
{
    // Route can be like "$router->get('/graphql', 'MyController@some');"
    public function some(RequestInterface $request, Endpoint $endpoint): array
    {
        return $endpoint->request(Factory::create($request));
    }
}
```

## Publishing a configs

- Run: `php artisan vendor:publish` 
- Open: `~/config/railgun.php`

# Dependency Injection

After installation you can select services from service container: 

- `Serafim\Railgun\Requests\RequestInterface::class` 
    > returns a GraphQL request object.
    
- `Serafim\Railgun\Contracts\Adapters\EndpointInterface::class` 
    > Already configurated GraphQL Endpoint class with requests and mutations.

- `Serafim\Railgun\Contracts\TypesRegistryInterface::class` 
    > Registry of GraphQL types.

# Events

You can subscribe on any events. Just use [events service](https://laravel.com/docs/5.4/events).

- `railgun.type:creating` 
    > Calls before a new GraphQL type was be registered
    
- `railgun.type:created` 
    > Calls after type registration
    
- `railgun.schema:creating` 
    > Calls before creating a new schema
    
- `railgun.schema:created` 
    > Calls after creating a new schema
