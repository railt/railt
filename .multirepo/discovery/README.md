<p align="center">
    <img src="https://railt.org/images/logo-dark.svg" width="200" alt="Railt" />
</p>
<p align="center">
    <a href="https://travis-ci.org/railt/discovery"><img src="https://travis-ci.org/railt/discovery.svg?branch=1.4.x" alt="Travis CI" /></a>
    <a href="https://codeclimate.com/github/railt/discovery/test_coverage"><img src="https://api.codeclimate.com/v1/badges/8229766c2341a1c2f192/test_coverage" /></a>
    <a href="https://codeclimate.com/github/railt/discovery/maintainability"><img src="https://api.codeclimate.com/v1/badges/8229766c2341a1c2f192/maintainability" /></a>
</p>
<p align="center">
    <a href="https://packagist.org/packages/railt/discovery"><img src="https://img.shields.io/badge/PHP-7.1+-6f4ca5.svg" alt="PHP 7.1+"></a>
    <a href="https://railt.org"><img src="https://img.shields.io/badge/official-site-6f4ca5.svg" alt="railt.org"></a>
    <a href="https://discord.gg/ND7SpD4"><img src="https://img.shields.io/badge/discord-chat-6f4ca5.svg" alt="Discord"></a>
    <a href="https://packagist.org/packages/railt/discovery"><img src="https://poser.pugx.org/railt/discovery/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/discovery"><img src="https://poser.pugx.org/railt/discovery/downloads" alt="Total Downloads"></a>
    <a href="https://raw.githubusercontent.com/railt/discovery/1.4.x/LICENSE.md"><img src="https://poser.pugx.org/railt/discovery/license" alt="License MIT"></a>
</p>

# Discovery

## Installation

- Install package using composer.

```bash
composer require railt/discovery
```

- Add discovering event into your `composer.json`.

```json
{
    "scripts": {
         "post-autoload-dump": [
             "Railt\\Discovery\\Manifest::discover"
         ]
     }
}
```

## Usage

Discovery provides the ability to implement a cross-package 
configuration using `composer.json`.

In order to access the configuration group, you must specify the key 
name in the `extra` section:

```json
{
    "extra": {
        "discovery": ["your-key"]
    }
}
```

## Values Export

Any group that is listed inside the `{"extra": {"discovery": ...}}` section 
will be available, exported and readable.

```json5
{
    "extra": {
        "discovery": ["example-2"],
        "example-1": "value", // This section will be IGNORED
        "example-2": "value" // Only this section will be exported
    }
}
```

## Reading Exported Values

After updating the composer dependencies, an object with the specified configs 
will be formed. In order to further read this data - you need to use the 
`Discovery` class.

```json
{
    "extra": {
        "discovery": ["config"],
        "config": {
            "commands": [
                "ExampleCommand1",
                "ExampleCommand2"
            ]
        }
    }
}
```

```php
<?php

$discovery = new Railt\Discovery\Discovery(__DIR__ . '/vendor');

$discovery->get('config.commands'); 
// array(2) { "ExampleCommand1", "ExampleCommand2" }
```

### Auto Detect

You can try to create a Discovery instance using automatic logic to determine 
the paths to the vendor directory.

```php
<?php

$discovery = Railt\Discovery\Discovery::auto();
```

### From ClassLoader

You can create a new Discovery instance from the Composer ClassLoader.

```php
<?php
// Composer ClassLoader
$loader = require __DIR__ . '/vendor/autoload.php';

$discovery = Railt\Discovery\Discovery::fromClassLoader($loader);
```

### From Composer

You can create instances of Discovery from Composer plugins using the 
appropriate static constructor.

```php
<?php
use Composer\Composer;
use Railt\Discovery\Discovery;

class ComposerPlugin
{
    public function __construct(Composer $composer)
    {
        $discovery = Discovery::fromComposer($composer);
    }
}
```

## Export Removal

In order to exclude any value from the export data - you need to 
register the necessary paths in the section `except:discovery`.

Please note that this rule is valid only in the root package `composer.json`.

```json5
{
    "extra": {
        "discovery:except": [
            "example-1",
            "example-2:child-1:a",
            "example-2:test:value-2"
        ],
        "example-1": { // This value should be skipped by rule "example-1"
            "key": "value"
        },
        "example-2": {
            "child-1": {
                "a": 1, // This value should be skipped by rule "example-2:child-1:a"
                "b": 2
            },
            "test": [
                "value-1",
                "value-2" // This value should be skipped by rule "example-2:test:value-2"
            ]
        }
    }
}
```

