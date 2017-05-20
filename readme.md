<p align="center">
    <img src="https://habrastorage.org/web/99f/a07/812/99fa078122d841c0be1ac805a0ee24d7.png" />
</p>

<p align="center">
    <a href="https://travis-ci.org/SerafimArts/Railgun">
        <img src="https://travis-ci.org/SerafimArts/Railgun.svg?branch=master">
    </a>
    <a href="https://scrutinizer-ci.com/g/SerafimArts/Railgun/?branch=master">
        <img src="https://scrutinizer-ci.com/g/SerafimArts/Railgun/badges/quality-score.png?b=master">
    </a>
    <a href="https://scrutinizer-ci.com/g/SerafimArts/Railgun/?branch=master">
        <img src="https://scrutinizer-ci.com/g/SerafimArts/Railgun/badges/coverage.png?b=master">
    </a>
    <a href="https://raw.githubusercontent.com/SerafimArts/Railgun/master/LICENSE">
        <img src="https://img.shields.io/badge/license-MIT-green.svg">
    </a>
</p>

This is a pure async PHP realization of the GraphQL protocol based on the 
[youshido/graphql](https://github.com/Youshido/GraphQL) and/or 
[webonyx/graphql-php](https://github.com/webonyx/graphql-php#fields)
core drivers of the official GraphQL Specification 
located on [Facebook GitHub](http://facebook.github.io/graphql/).

GraphQL is a modern replacement of the almost obsolete REST approach to present API. 
It's been almost 16 years since the REST idea was found in 2000 by Roy Fielding. 
With all credit to everything we accomplished using REST it's time to change for 
something better. GraphQL advanced in many ways and has fundamental 
improvements over the old good REST:

- Self-checks embedded on the ground level of your backend architecture
- Reusable API for different client versions and devices, i.e. no more need in maintaining "/v1", "/v2" or "/v10002545.07E20"
- A complete new level of distinguishing of the backend and frontend logic
- Easily generated documentation and incredibly intuitive way to explore created API
- Once your architecture is complete – most client-based changes does not require backend modifications
- It could be hard to believe but give it a try and you'll be rewarded with much better architecture and so much easier to support code.

## Requirements

- PHP 7.1+
- Composer
- GraphQL base driver (one of):
    - [youshido/graphql (1.4+)](https://github.com/Youshido/GraphQL) (Not Implemented Yet)
    - [webonyx/graphql-php (0.9+)](https://github.com/webonyx/graphql-php#fields)
- Core (one of):
    - [laravel/framework (5.1+)](https://github.com/laravel/framework)
    - [symfony/symfony (2.8+)](https://github.com/symfony/symfony)
    - or nothing (native php)

## Usage

> This package currently are under development. API can contains a little (or many) broken changes in future. 

### Types

```php
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

### Queries

```php
class UsersQuery extends AbstractQuery
{
    public function getType(): TypeDefinitionInterface
    {
        return $this->listOf(User::class);
    }

    public function resolve($value, array $arguments = [])
    {
        return [
            [
                'id'       => 23,
                'comments' => [
                    ['id' => 1, 'content' => 'first content'],
                    ['id' => 2, 'content' => 'second content'],
                    ['id' => 3, 'content' => 'third content'],
                ],
            ],
        ];
    }
}
```

### Simple HTTP Endpoint

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
        // Symfony, Laravel or Native (just send nothing aka null) request objects
        Request::createFromGlobals()
    )
);

(new JsonResponse($response))->send();
```

