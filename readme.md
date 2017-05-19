Railgun
-------

GraphQL implementation for Symfony and Laravel

## Requirements

- PHP 7.1+
- Composer
- GraphQL base driver (one of):
    - [youshido/graphql (1.4+)](https://github.com/Youshido/GraphQL) 
        > Careful: Not Implemented Yet
    - [webonyx/graphql-php (0.9+)](https://github.com/webonyx/graphql-php#fields)
- Core:
    - [laravel/framework (5.1+)](https://github.com/laravel/framework)
    - [symfony/symfony (2.8+)](https://github.com/symfony/symfony)

## Types

```php
<?php
use Serafim\Railgun\Types\AbstractObjectType;

class Comment extends AbstractObjectType
{
    // public function getFields(): iterable { ... }
}

class User extends AbstractObjectType
{
    public function getFields(): iterable
    {
        yield 'id' => $this->id(); // or $this->field('id')
        yield 'login' => $this->string();
        yield 'comments' => $this->hasMany(Comment::class);
    }
}
```

## Queries

> TODO

```php
/**
 * Class ArticlesQuery
 * @package Serafim\Railgun\Tests\Queries
 */
class UsersQuery extends FieldDefinition
{
    /**
     * UsersQuery constructor.
     */
    public function __construct()
    {
        parent::__construct(User::class);

        $this->many()->then(function () {
            return [
                [
                    'id'       => 23,
                    'login'    => 'Vasya',
                    'comments' => [ ... ],
                ],
            ];
        });
    }
}
```

## Simple Endpoint

```php
use Illuminate\Http\Request;
use Serafim\Railgun\Endpoint;
use Illuminate\Http\JsonResponse;
use Serafim\Railgun\Requests\Factory;

require __DIR__ . '/../vendor/autoload.php';


$endpoint = (new Endpoint('test'))
    ->query('users', new UsersQuery());


$response = $endpoint->request(
    Factory::create(
        // Symfony, Laravel or Native (null) request objects
        Request::createFromGlobals()
    )
);

(new JsonResponse($response))->send();
```

