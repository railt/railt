<p align="center">
    <a href="https://railt.org"><img src="https://avatars.githubusercontent.com/u/31258828?s=300" width="150" alt="Railt" /></a>
</p>
<p align="center">
    <a href="https://packagist.org/packages/railt/railt"><img src="https://poser.pugx.org/railt/railt/require/php?style=for-the-badge" alt="PHP 8.1+"></a>
    <a href="https://railt.org"><img src="https://img.shields.io/badge/docs-site-6f4ca5.svg?style=for-the-badge&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAclBMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////9eWEHEAAAAJXRSTlMAoBzg8fxU9iFgsvjQwblyZdQYrYR0a1oT6dqlkH93TjQNC6N2001YMwAAAM5JREFUOMvNUtkOgzAMS3pwdMA4BjvZxfz/v7hOIEAt2hMP+MWN4kRRbdoYxMfIMJSmFsvtTmOA5gVJoLADkigqYB8qcPsNUOVQpd2kkFdA48wDe8rQkkWLjPbAfIedsv2T1uWvKLU+WYWa39HBEB2R9lWKI1EFphUhD5QpyqXLo4DTd4gns8ujIA6Dc3G/xC6PggjJ9ZYgcnl2BOIHpMcTGOK1Y4/XBPtfbcB/zapHs3y7D5PdfmBEHxgDNMuRK6bIeRDshtaX1EPsS9oWvv3QFx9Wvu0UAAAAAElFTkSuQmCC" alt="railt.org"></a>
    <a href="https://discord.gg/ND7SpD4"><img src="https://img.shields.io/badge/discord-chat-6f4ca5.svg?style=for-the-badge&logo=discord&logoColor=ffffff" alt="Discord"></a>
    <a href="https://packagist.org/packages/railt/railt"><img src="https://poser.pugx.org/railt/railt/v?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/railt"><img src="https://poser.pugx.org/railt/railt/v/unstable?style=for-the-badge" alt="Total Downloads"></a>
    <a href="https://raw.githubusercontent.com/railt/railt/master/LICENSE.md"><img src="https://poser.pugx.org/railt/railt/license?style=for-the-badge" alt="License MIT"></a>
</p>
<p align="center">
    <a href="https://github.com/railt/railt/actions?workflow=Testing"><img src="https://github.com/railt/railt/workflows/tests/badge.svg" alt="Testing" /></a>
</p>

## Introduction

Project idea is clean and high-quality code.

Unlike most (all at the moment) implementations, like [webonyx](https://github.com/webonyx/graphql-php),
[youshido](https://github.com/youshido-php/GraphQL) or [digitalonline](https://github.com/digiaonline/graphql-php)
the Railt contains a completely own implementation of the GraphQL SDL parser
which is based on [EBNF-like grammar](https://github.com/railt/railt/tree/master/libs/sdl/resources/grammar). 
This opportunity allows not only to have the 
[original implementation of the language](https://facebook.github.io/graphql/draft/) and to
keep it always up to date, but also to implement [a new backward compatible
functionality](https://github.com/railt/railt/projects/1) that is not available
to other implementations.

Goal of Railt:
- Do not repeat the mistakes made in the JS-based implementations.
- Implement a modern and convenient environment for PHP developers.
- Implement easy integration into any ready-made solutions based on PSR.
- Provide familiar functionality (including dependency injection, routing, etc.).

## Installation

- `composer require railt/railt`

## Quick Start

This tutorial helps you:

- Obtain a basic understanding of GraphQL principles.
- Define a GraphQL schema that represents the structure of your data set.
- Run an instance of Railt Application that lets you execute queries
  against your schema.

This tutorial assumes that you are familiar with the command line and PHP and
have installed a recent PHP (v8.1+) version.

### Step 1: Create a new project

1. From your preferred development directory, create a directory for a new
   project and `cd` into it:

```bash
mkdir railt-example
cd railt-example
```

2. Initialize a new project with Composer:

```bash
composer init
composer require railt/railt dev-master@dev
```

> Your project directory now contains a `composer.json` file.

> Please note that in case of installation errors related to installing
> the dev version (_"The package is not available in a stable-enough version
> according to your minimum-stability setting"_), you need to specify
> `"minimum-stability": "dev"` in `composer.json` file.
>
> See more at [https://getcomposer.org/doc/04-schema.md#minimum-stability](https://getcomposer.org/doc/04-schema.md#minimum-stability)

Applications that run Railt Application may require two top-level dependencies:

- `railt/webonyx-executor` - An executor that provides a
  [webonyx/graphql-php](https://github.com/webonyx/graphql-php) bridge for
  launching and processing GraphQL requests.
- `railt/router-extension` - A router extension that provides a convenient way
  to delegate GraphQL requests to controller instances.

> Alternatively, you can install all components separately:
> ```bash
> composer require railt/factory railt/webonyx-executor railt/router-extension
> ```

### Step 2: Define your GraphQL schema

Every GraphQL application (including Railt) uses a schema to define the
structure of data that clients can query. In this example, we'll create an
application for querying a collection of users by `id` and `name`.

Open `index.graphqls` in your preferred code editor and paste the following
into it:

```graphql
# Comments in GraphQL strings (such as this one)
# start with the hash (#) symbol.

# This "User" type defines the queryable fields for
# every user in our data source.
type User {
    id: ID
    name: String
}

# The "Query" type is special: it lists all of the
# available queries that clients can execute, along with
# the return type for each. In this case, the "books"
# query returns an array of zero or more Books (defined above).
type Query {
    users: [User]
}
```

Now just open (create) the `index.php` file and paste the following into it:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

//
// Create An Application
//
$application = new Railt\Foundation\Application(
    executor: new Railt\Executor\Webonyx\WebonyxExecutor(),
);

$application->extend(new Railt\Extension\Router\DispatcherExtension());

//
// Creating a connection instance that will process
// incoming requests and return responses.
//
$connection = $application->connect(
    schema: new \SplFileInfo(__DIR__ . '/index.graphqls'),
);
```

This snippet defines a simple, valid GraphQL schema. Clients will be able to
execute a query named `users`, and our server will return an array of zero
or more `User`s.

#### Step 2.1: Schema health check

To health check an application, you can create a `GraphQLRequest` instance
manually by passing the request object with the desired GraphQL query string.

```php
//
// Passing a request to the specified connection.
// 
$response = $connection->handle(
    request: new \Railt\Http\GraphQLRequest(
        query: '{ users { id, name } }',
    ),
);

dump($response->toArray());

//
// Expected Output:
//
// array:1 [
//   "data" => array:1 [
//     "users" => []
//   ]
// ]
//
```

### Step 3: Define controller

Resolvers tell Railt Application how to fetch the data associated with a
particular type. Because our `User` array is hardcoded, the corresponding
resolver is straightforward.

Create a controller file with a `UserController` class, for example with
a `index()` method and the following code:

```php
<?php

class UserController
{
    public function index(): iterable
    {
        return [
            ['id' => 1, 'name' => 'Vasya'],
            ['id' => 2, 'name' => 'Petya'],
        ];
    }
}
```

> Make sure that this class is available for autoloading or the file is
> included in the `index.php`.

### Step 4: Bind field to controller action

We've defined our data set, but Railt application doesn't know that it should
use that data set when it's executing a query. To fix this, we create a route.

Route tell Railt how to fetch the data associated with a particular type.
Because our `User` array is hardcoded, the corresponding route is
straightforward.

Add the following `@route` directive to the bottom of your `index.graphqls` file:

```graphql
# ...
type User {
    id: ID
    name: String
}

# ...
type Query {
    users: [User]
        # Route directive can be defined here
        @route(action: "UserController->index")
}
```

### Step 5: Working with HTTP

To pass the request data and send the response, we must complete
our `index.php` file.

> {tip} In the case that you use [Symfony](https://symfony.com/doc/current/introduction/http_fundamentals.html),
[Laravel](https://laravel.com/docs/10.x/requests) or another http layer
(for example, [psr-7](https://www.php-fig.org/psr/psr-7/)), then you can
organize data acquisition according to the provided framework API and/or
specification.

```php
$data = json_decode(file_get_contents('php://input'), true);

$response = $connection->handle(
    request: new \Railt\Http\GraphQLRequest(
        query: $data['query'] ?? '',
        variables: $data['variables'] ?? [],
        operationName: $data['operationName'] ?? null,
    ),
);

$json = json_encode($response->toArray());

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo $json;
```

### Step 6: Start the server

We're ready to start our server! Run the following command from your
project's root directory:

```bash
php -S 127.0.0.0:80
```

You should now see the following output at the bottom of your terminal:

```bash
PHP 8.2.6 Development Server (http://127.0.0.1:80) started
```

We're up and running!

### Step 7: Execute your first query

We can now execute GraphQL queries on our server. To execute our first query,
we can use [Apollo Sandbox](https://studio.apollographql.com/sandbox/),
[GraphQL Playground](https://www.graphqlbin.com/v2/new) or something else.

Our server supports a single query named `users`. Let's execute it!

Here's a GraphQL query string for executing the `users` query:
```graphql
{
  users {
    id
    name
  }
}
```

Paste this string into the query panel and click the "send request" button (The
GraphQL interface and panel layout may depend on the platform/client you are
using). The results (from our hardcoded data set) appear in the response panel:

![/img/get-started-request.png](https://railt.org/img/get-started-request.png)

> One of the most important concepts of GraphQL is that clients can choose
to query only for the fields they need. Delete `name` from the query string
and execute it again. The response updates to include only the `id` field for
each `User`!


## Learning Railt

Full documentation can be found on the [official site](https://railt.org/).

## Contributing

Thank you for considering contributing to the Railt Framework! 
The contribution guide can be found in the [documentation](https://railt.org/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Railt, please send an e-mail to maintainer 
at nesk@xakep.ru. All security vulnerabilities will be promptly addressed.

## License

The Railt Framework is open-sourced software licensed under 
the [MIT license](https://opensource.org/licenses/MIT).

## Help & Community [![Discord](https://img.shields.io/badge/discord-chat-6f4ca5.svg?style=for-the-badge&logo=discord&logoColor=ffffff)](https://discord.gg/ND7SpD4)

Join our [Discord community](https://discord.gg/ND7SpD4) if you run into issues or have questions. We love talking to you!

<p align="center"><a href="https://discord.gg/ND7SpD4"><img src="https://habrastorage.org/webt/mh/s4/hg/mhs4hg2eb0roaix7igak0syhcew.png" /></a></p>

## Supported By

<p align="center">
    <a href="https://www.jetbrains.com/" target="_blank"><img src="https://phplrt.org/img/thanks/jetbrains.svg" alt="JetBrains" /></a>
</p>
