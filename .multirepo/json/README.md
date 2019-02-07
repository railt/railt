<p align="center">
    <img src="https://railt.org/images/logo-dark.svg" width="200" alt="Railt" />
</p>

<p align="center">
    <a href="https://travis-ci.org/railt/json"><img src="https://travis-ci.org/railt/json.svg?branch=1.4.x" alt="Travis CI" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/json/?branch=1.4.x"><img src="https://scrutinizer-ci.com/g/railt/json/badges/quality-score.png?b=1.4.x" alt="Scrutinizer CI" /></a>
    <a href="https://packagist.org/packages/railt/json"><img src="https://poser.pugx.org/railt/json/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/json"><img src="https://poser.pugx.org/railt/json/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/railt/json/master/LICENSE"><img src="https://poser.pugx.org/railt/json/license" alt="License MIT"></a>
</p>

# Json

> Note: All questions and issues please send 
to [https://github.com/railt/railt/issues](https://github.com/railt/railt/issues)

## JSON RFC-7159 Usage

Small examples on working with the RFC-7159 specification.

### Encoding

```php
<?php

use Railt\Json\Json;
use Railt\Json\Exception\JsonException;

try {
    $json = Json::encode([1, 2, 3]);
} catch (JsonException $exception) {
    // Exception handling
}
```

### Decoding

```php
<?php

use Railt\Json\Json;
use Railt\Json\Exception\JsonException;

try {
    $data = Json::decode(<<<JSON
        {
            "quotes": "I can use \"double quotes\" here",
            "float": 0.8675309,
            "number": 42,
            "array": ["a", "b", "c"]
        }
    JSON);
} catch (JsonException $exception) {
    // Exception handling
}
```

## JSON5 Usage

### Encoding

```php
<?php

use Railt\Json\Json5;
use Railt\Json\Exception\JsonException;

try {
    $json = Json5::encode([1, 2, 3]);
} catch (JsonException $exception) {
    // Exception handling
}
```

### Decoding

```php
<?php

use Railt\Json\Json5;
use Railt\Json\Exception\JsonException;

try {
    $data = Json5::decode(<<<'JSON5'
        // Simple example of JSON5 spec
        {
            unquoted: 'and you can quote me on that',
            singleQuotes: 'I can use "double quotes" here',
            lineBreaks: "Look, Mom! \
            No \\n's!",
            hexadecimal: 0xdecaf,
            leadingDecimalPoint: .8675309, andTrailing: 8675309.,
            positiveSign: +1,
            trailingComma: 'in objects', andIn: ['arrays',],
            "backwardsCompatible": "with JSON",
        }
    JSON5);
} catch (JsonException $exception) {
    // Exception handling
}
```
