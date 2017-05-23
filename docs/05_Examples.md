# Examples

All examples can be [found here](https://github.com/SerafimArts/Railgun/tree/master/examples).

## Startup

- Open `~/example` directory.
- Execute a `php -S 0.0.0.0:8080`.
- Open `http://127.0.0.1:8080/graphiql.php` into your Web Browser.

## Structure

- `Models/`
    > This directory contains models from your application. 
    They can be completely different and do not 
    necessarily have to follow any structure. 
    These can be the entities of the **Doctrine ORM**, 
    the models of the **Eloquent ORM** or in general anything...
- `Serializers/`
    > This directory contains rules for converting models (entities)
    to a primitive array type.
- `Queries/`
    > This directory contains a list of "query" requests for your endpoints.
- `Mutations/`
    > This directory contains a list of "mutation" requests for your endpoints.
- `Types/`
    > Here is a list of composite **GraphQL** types, 
    such as **Objects**, **Interfaces**, **Enums** and **Unions**.


