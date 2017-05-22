<p align="center">
    <img src="https://habrastorage.org/web/c4f/7b8/a43/c4f7b8a430814a7e924d4419e685ec92.png" alt="Railgun" />
</p>

<p align="center">
    <a href="https://travis-ci.org/SerafimArts/Railgun">
        <img src="https://travis-ci.org/SerafimArts/Railgun.svg?branch=master" alt="Travis CI" />
    </a>
    <a href="https://scrutinizer-ci.com/g/SerafimArts/Railgun/?branch=master">
        <img src="https://scrutinizer-ci.com/g/SerafimArts/Railgun/badges/quality-score.png?b=master" alt="Scrutinizer CI" />
    </a>
    <a href="https://scrutinizer-ci.com/g/SerafimArts/Railgun/?branch=master">
        <img src="https://scrutinizer-ci.com/g/SerafimArts/Railgun/badges/coverage.png?b=master" alt="Code coverage" />
    </a>
    <a href="https://packagist.org/packages/serafim/railgun">
        <img src="https://poser.pugx.org/serafim/railgun/version" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/serafim/railgun">
        <img src="https://poser.pugx.org/serafim/railgun/v/unstable" alt="Latest Unstable Version">
    </a>
    <a href="https://raw.githubusercontent.com/SerafimArts/Railgun/master/LICENSE">
        <img src="https://poser.pugx.org/serafim/railgun/license" alt="License MIT">
    </a>
</p>


## Table of contents

- [About](#about)
- [Requirements](#requirements)
- [Installation](#installation)
    - [Symfony](#symfony-integration)
    - [Laravel](#laravel-integration)
    - [Zend](#zend-integration)
    - [ReactPHP](#reactphp-integration)
    - [Appserver](#appserver-integration)
    - [PPM (PHP Process Manager)](#ppm-integration)
- [Usage](#usage)
    - [Types](#types)
    - [Queries](#queries)
- [Advanced usage](#advanced-usage)
    - [Serializers](#serializers)
    - [Schema extenders](#schema-extenders)
    
## About

This is a pure async PHP realization of the **GraphQL** protocol based on the 
[youshido/graphql](https://github.com/Youshido/GraphQL) and/or 
[webonyx/graphql-php](https://github.com/webonyx/graphql-php#fields)
core drivers of the official GraphQL Specification 
located on [Facebook GitHub](http://facebook.github.io/graphql/).

**GraphQL** is a modern replacement of the almost obsolete **REST** approach to present API. 
It's been almost 16 years since the **REST** idea was found in 2000 by Roy Fielding. 
With all credit to everything we accomplished using REST it's time to change for 
something better. **GraphQL** advanced in many ways and has fundamental 
improvements over the old good **REST**:

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
    - [webonyx/graphql-php (0.9+)](https://github.com/webonyx/graphql-php#fields)
    - TODO: [youshido/graphql (1.4+)](https://github.com/Youshido/GraphQL)
- Frameworks (one of):
    - [laravel/framework (5.1+)](https://github.com/laravel/framework)
    - [symfony/symfony (2.8+)](https://github.com/symfony/symfony)
    - or nothing (native php)
    - TODO: [reactphp/http](https://github.com/reactphp/http)
    - TODO (?): [zendframework/zendframework](https://github.com/zendframework/zendframework)
    - TODO (?): [appserver-io/appserver](https://github.com/appserver-io/appserver)
    - TODO (?): [php-pm/php-pm](https://github.com/php-pm/php-pm)

## Installation

- `composer require serafim/railgun`

### Symfony integration

```php
use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Requests\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MyController
{
    /**
     * @Route("/graphql", methods={"GET", "POST")
     */
    public function someAction(Request $request): JsonResponse
    {
        $endpoint = (new Endpoint('test'))
            ->query('articles', new ArticlesQuery());
            
        $response = $endpoint->request(Factory::create($request));
        
        return new JsonResponse($response);
    }
}
```

### Laravel integration

1) Add a ServiceProvider into `config/app.php`

```php
    'providers' => [
        // ...
        Serafim\Railgun\Providers\Laravel\LaravelServiceProvider::class,
    ]
```

2) Create your controller

```php
use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Requests\RequestInterface;

class MyController
{
    // $router->get('/graphql', 'MyController@some');
    public function some(RequestInterface $request, Endpoint $endpoint): array
    {
        return $endpoint->request(Factory::create($request));
    }
}
```

### Zend integration

> TODO

### ReactPHP integration

> TODO

### Appserver integration

> TODO

### PPM integration

> TODO


## Usage

> This package currently are under development. 
> API can contains a little (or many) broken changes in future. 

### Types

```php
use Serafim\Railgun\Types\Schemas\Fields;
use Serafim\Railgun\Types\AbstractObjectType;

class Comment extends AbstractObjectType
{
    // public function getFields(Fields $schema): iterable { ... }
}

class User extends AbstractObjectType
{
    public function getFields(Fields $schema): iterable
    {
        yield 'id'         => $schema->id();
        yield 'created_at' => $schema->string();
        yield 'updated_at' => $schema->string();
        
        // Relation to list of "Comment" type
        yield 'comments'   => $schema->hasMany(Comment::class);
    }
}
```

### Queries

```php
use Serafim\Railgun\Types\Schemas\TypeDefinition;

class UserQuery extends AbstractQuery
{
    public function getType(TypeDefinition $schema): TypeDefinitionInterface
    {
        return $schema->typeOf(User::class);
    }

    public function resolve($value, array $arguments = [])
    {
        return [ 
            'comments' => [
                ['id' => 1, 'content' => 'first content'],
                ['id' => 2, 'content' => 'second content'],
                ['id' => 3, 'content' => 'third content'],
            ]
        ];
    }
}
```

## Advanced usage

### Serializers

Creating a serializer:

```php
use Serafim\Railgun\Serializers\AbstractSerializer;

class UserSerializer extends AbstractSerializer
{
    /**
     * @param MyUserObject $user
     */
    public function toArray($user): array
    {
        return [
            'id'         => $user->getId(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt(),
        ];
    }
}
```

Usage:

```php
class UserQuery extends AbstractQuery
{
    // ...
    public function resolve($value, array $arguments = [])
    {
        // $repo = UsersRespoitory::class
    
        retrun UserSerializer::items($repo->findAll());
    }
}
```

### Schema extenders

Add a new method (`timestamps()` as example):

```php
use Serafim\Railgun\Types\Schemas\Fields;

// Take a Fields shema instance
$fields = $endpoint->getRegistry()->schema(Fields::class);

// Extend schema using method `timestamps`
$fields->extend('timestamps', function (string $c = 'created_at', string $u = 'updated_at') use ($fields) {
    yield $c => $fields->string();
    yield $u => $fields->string();
});
```

And use it:

```php
class User extends AbstractObjectType
{
    /**
     *
     * Definition example:
     * <code>
     * [ 
     *  'id' => 'ID',
     *  'created_at' => 'string',
     *  'updated_at' => 'string'
     * ]
     * </code>
     */
    public function getFields(Fields $schema): iterable
    {
        yield 'id' => $schema->id();
        yield from $schema->timestamps();
    }
}
```
