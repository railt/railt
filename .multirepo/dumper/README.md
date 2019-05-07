<p align="center">
    <img src="https://railt.org/images/logo-dark.svg" width="200" alt="Railt" />
</p>
<p align="center">
    <a href="https://travis-ci.org/railt/dumper"><img src="https://travis-ci.org/railt/dumper.svg?branch=1.4.x" alt="Travis CI" /></a>
    <a href="https://codeclimate.com/github/railt/dumper/test_coverage"><img src="https://api.codeclimate.com/v1/badges/e22fba6228b1fa641e10/test_coverage" /></a>
    <a href="https://codeclimate.com/github/railt/dumper/maintainability"><img src="https://api.codeclimate.com/v1/badges/e22fba6228b1fa641e10/maintainability" /></a>
</p>
<p align="center">
    <a href="https://packagist.org/packages/railt/dumper"><img src="https://img.shields.io/badge/PHP-7.1+-6f4ca5.svg" alt="PHP 7.1+"></a>
    <a href="https://railt.org"><img src="https://img.shields.io/badge/official-site-6f4ca5.svg" alt="railt.org"></a>
    <a href="https://discord.gg/ND7SpD4"><img src="https://img.shields.io/badge/discord-chat-6f4ca5.svg" alt="Discord"></a>
    <a href="https://packagist.org/packages/railt/dumper"><img src="https://poser.pugx.org/railt/dumper/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/dumper"><img src="https://poser.pugx.org/railt/dumper/downloads" alt="Total Downloads"></a>
    <a href="https://raw.githubusercontent.com/railt/dumper/1.4.x/LICENSE.md"><img src="https://poser.pugx.org/railt/dumper/license" alt="License MIT"></a>
</p>

# Dumper

> Note: All questions and issues please send 
to [https://github.com/railt/railt/issues](https://github.com/railt/railt/issues)

## Usage

Component for **short** dump types. Can be used as a display of values in exceptions 
or short messages without detailed disclosure of the internal structure.

```php
<?php

echo dump_type(function (string $message = 'Hello World!'): string {
    return $message;
});

// fn((string $message = "Hello World!") -> string)
```


```php
<?php

echo dump_type(new ArrayIterator([1, 2, 3, 0.2]));

// object(ArrayIterator#18<int|float>)
```
