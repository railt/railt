<p align="center">
    <img src="https://railt.org/img/logo-dark.svg" alt="Railt" />
</p>

<p align="center">
    <a href="https://travis-ci.org/railt/railt"><img src="https://travis-ci.org/railt/railt.svg?branch=master" alt="Travis CI" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/railt/?branch=master"><img src="https://scrutinizer-ci.com/g/railt/railt/badges/coverage.png?b=master" alt="Code coverage" /></a>
    <a href="https://styleci.io/repos/91753282?branch=master"><img src="https://styleci.io/repos/91753282/shield?b=master" alt="StyleCI" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/railt/?branch=master"><img src="https://scrutinizer-ci.com/g/railt/railt/badges/quality-score.png?b=master" alt="Scrutinizer CI" /></a>
    <a href="https://packagist.org/packages/railt/railt"><img src="https://poser.pugx.org/railt/railt/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/railt"><img src="https://poser.pugx.org/railt/railt/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/railt/railt/master/LICENSE"><img src="https://poser.pugx.org/railt/railt/license" alt="License MIT"></a>
</p>

## Introduction

This is a pure PHP realization of the **GraphQL** protocol based on the 
[youshido/graphql](https://github.com/Youshido/GraphQL) and/or 
[webonyx/graphql-php](https://github.com/webonyx/graphql-php#fields) 
implementations of the official GraphQL Specification 
located on [Facebook GitHub](http://facebook.github.io/graphql/).

The difference from the above implementations is that the Railt provides the 
ability to describe the types and extended control of their behavior, 
thereby solving problems such as:

- Simplifying type declarations
- Types reusage
- Significant simplification of the construction of the API
- More flexible integration with frameworks (e.g. 
[Laravel](https://github.com/laravel/framework) or [Symfony](https://github.com/symfony/symfony))
- And others

## Quick start

The documentation is in the process of writing, therefore, in order to understand how it works, a quick start.

> `index.php`

```php
use Railt\Http\Request;
use Railt\Compiler\Compiler;
use Railt\Foundation\Application;
use Railt\Reflection\Filesystem\File;

$app = new Application(new Compiler());

$schema = File::fromFile(__DIR__ . '/schema.graphqls');

$response = $app->request(new Request(), $schema);
$response->send();
```

> `schema.graphqls`

```graphql
schema {
    query: Example
}

type Example {
    say(message: String = "Hello"): String! 
        @route(action: "ExampleController@say")
}
```

> `ExampleController.php`

```php
use Railt\Routing\Contracts\InputInterface as Input;

class ExampleController
{
    public function say(Input $input): string
    {
        return $input->get('message');
    }
}
```

> GraphQL

```graphql
# Request
{
    say(message: "Something is awesome!")
}
```

```json
// Response
{
    "say": "Something is awesome!"
}
```


## Learning Railt

> This documentation can contain NOT RELEVANT information and currently in progress.

- [Russian](https://ru.railt.org)
- [English](https://en.railt.org)

## Contributing

Thank you for considering contributing to the Railt Framework! 
The contribution guide can be found in the [documentation](https://railt.org/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Railt, please send an e-mail to maintainer 
at nesk@xakep.ru. All security vulnerabilities will be promptly addressed.

## License

The Railt Framework is open-sourced software licensed under 
the [MIT license](https://opensource.org/licenses/MIT).

The Railt\Parser, which is part of the Railt Framework re-distribute 
under the [BSD-3-Clause license](https://opensource.org/licenses/BSD-3-Clause).

## Production ready

> Not ready for real world usage yet :bomb: :scream:

| Package                   | Release                                                                          |
|---------------------------|----------------------------------------------------------------------------------|
| `railt/railt`             | ![Latest Stable Version](https://poser.pugx.org/railt/railt/version)             |
| `railt/webonyx-adapter`   | ![Latest Stable Version](https://poser.pugx.org/railt/webonyx-adapter/version)   |
| `railt/youshido-adapter`  | ![Latest Stable Version](https://poser.pugx.org/railt/youshido-adapter/version)  |
| `railt/laravel-provider`  | ![Latest Stable Version](https://poser.pugx.org/railt/laravel-provider/version)  |
| `railt/symfony-bundle`    | ![Latest Stable Version](https://poser.pugx.org/railt/symfony-bundle/version)    |

| Component                 | Release                                                                          |
|---------------------------|----------------------------------------------------------------------------------|
| `railt/parser`            | ![Latest Stable Version](https://poser.pugx.org/railt/parser/version)            |
| `railt/compiler`          | ![Latest Stable Version](https://poser.pugx.org/railt/compiler/version)          |
| `railt/reflection`        | ![Latest Stable Version](https://poser.pugx.org/railt/reflection/version)        |
| `railt/container`         | ![Latest Stable Version](https://poser.pugx.org/railt/container/version)         |
| `railt/events`            | ![Latest Stable Version](https://poser.pugx.org/railt/events/version)            |
| `railt/http`              | ![Latest Stable Version](https://poser.pugx.org/railt/http/version)              |
| `railt/routing`           | ![Latest Stable Version](https://poser.pugx.org/railt/routing/version)           |
