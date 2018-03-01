# Release Notes

## 1.2.0 (master)

### Framework Additionals

- New Lexer kernel (faster than old algorithm more than 150 times).
- New AST compiler structure.
- Error correction with type inheritance of another type.
- New Call Stack structure.
- The compiler has removed the dictionary methods.
- Simplify exclusion code (a separate class of CallStack renderer is created).
- Added a Call Stack Observer class.
- A single dictionary of generalized types is created.
- Significantly improved code and structure of tests.
- Added support for recursive build of invocation types.
- Added method of adding extensions `$app->extend(MyExtension::class)`.

### GraphQL Additionals

- Added a directive "`DOCUMENT`" location (`directive @xxx on DOCUMENT`).
- Added validation of location directives.
- The possibility of schema naming was added (`schema XXX {}` instead of `schema {}`).
- Added support for multiple directives of the same name (`type Some @directive @directive @directive {}`).

### Changed

- The `Railt/Foundation/ServiceProviders/...` was renamed to `Railt/Foundation/Extensions/...`
- The `Railt/Routing/Contracts/InputInterface` was renamed to `Railt/Http/InputInterface`
- The router is no longer the default extension and requires a separate connection.

### Fixed

- Correction of cloning of a CallStack (empty trace in recursive exceptions).
- Correction of the exception position in the source code from a variable (not from a file).
- Fix error message with recursive autoload (An attempt to load an invalid type that contains a non-existent type).
- Correcting validation of arguments of directives and objects.
- Fix unexpected string lexemes while a string value an empty.
