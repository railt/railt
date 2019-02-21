<p align="center">
    <img src="https://railt.org/images/logo-dark.svg" width="200" alt="Railt" />
</p>

<p align="center">
    <a href="https://travis-ci.org/railt/dumper"><img src="https://travis-ci.org/railt/dumper.svg?branch=1.4.x" alt="Travis CI" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/dumper/?branch=1.4.x"><img src="https://scrutinizer-ci.com/g/railt/dumper/badges/quality-score.png?b=1.4.x" alt="Scrutinizer CI" /></a>
    <a href="https://packagist.org/packages/railt/dumper"><img src="https://poser.pugx.org/railt/dumper/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/dumper"><img src="https://poser.pugx.org/railt/dumper/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/railt/dumper/master/LICENSE.md"><img src="https://poser.pugx.org/railt/dumper/license" alt="License MIT"></a>
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
