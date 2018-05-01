Charcoal Config
===============

[![License][badge-license]][charcoal-config]
[![Latest Stable Version][badge-version]][charcoal-config]
[![Code Quality][badge-scrutinizer]][dev-scrutinizer]
[![Coverage Status][badge-coveralls]][dev-coveralls]
[![SensioLabs Insight][badge-sensiolabs]][dev-sensiolabs]
[![Build Status][badge-travis]][dev-travis]

A Charcoal component for organizing configuration data and designing object data models.

This component is the glue for much of the [Charcoal framework][charcoal-app].



## Table of Contents

1.  [Installation](#installation)
    1.  [Requirements](#requirements)
2.  [Entity & Config](#entity--config)
3.  [Features](#features)
    1.  [File Loader](#file-loader)
    2.  [Key Separator Lookup](#key-separator-lookup)
    3.  [Delegated Data Lookup](#delegates-lookup)
    4.  [Array Access](#array-access)
    5.  [Interoperability](#interoperability)
    6.  [Configurable objects](#configurable-objects)
4.  [Development](#development)
    1. [API Documentation](#api-documentation)
    2. [Development Dependencies](#development-dependencies)
    3. [Coding Style](#coding-style)
5.  [Credits](#credits)
6.  [License](#license)



## Installation

The preferred (and only supported) method is with Composer:

```shell
$ composer require locomotivemtl/charcoal-config
```



### Requirements

-   **PHP 5.6+**: _PHP 7_ is recommended.
-   [**PSR-11**][psr-11]: The container interface.



## Entity & Config

In Charcoal, data is organized into two primary object types: `Entity` and `Config`.

-   **Entities** are simple data containers that implement `ArrayAccess`, `JsonSerializable`, and `Serializable`.  
    It provides the following public methods: `keys()`, `data()`, `setData()`, `has()`, `get()`, `set()`.
-   **Configs** are advanced _Entities_, with hierarchical storage and file loader support, that also implement `IteratorAggregate`, `Psr\Container\ContainerInterface`, `Charcoal\Config\SeparatorAwareInterface`, and `Charcoal\Config\DelegatesAwareInterface`.  
    In addition to extending `Entity`, it provides the following public methods: `defaults()`, `merge()`, `addFile()`, and `loadFile()`.  
    Configs are used for managing runtime configuration data such as application preferences, service options, and object settings.



## Features

-   [Read data from INI, JSON, PHP, and YAML files](#file-loader)
-   [Customizable separator for nested lookup](#key-separator-lookup)
-   [Share configuration entries](#delegates-lookup)
-   [Array accessible entities](#array-access)
-   [Interoperable datasets](#interoperability)
-   [Configurable objects](#configurable-objects)



### File Loader

The _Config_ container currently supports four file formats: INI, JSON, PHP, and YAML.

A configuration file can be imported into a Config object via the `addFile($path)` method, or by direct instantiation:

```php
use Charcoal\Config\GenericConfig as Config;

$cfg = new Config('config.json');
$cfg->addFile('config.yml');
```

The file's extension will be used to determine how to import the file.
The file will be parsed and, if its an array, will be merged into the container.

If you want to load a configuration file _without_ adding its content to the Config, use `loadFile($path)` instead.
The file will be parsed and returned regardless if its an array.

```php
$data = $cfg->loadFile('config.php');
```

Check out the [documentation](docs/file-loader.md) and [examples](tests/Charcoal/Config/Fixture/pass) for more information.



### Key Separator Lookup

It is possible to lookup, retrieve, assign, or merge values in multi-dimensional arrays using _key separators_.

In Config objects, the default separator is the period character (`.`). The token can be retrieved with the `separator()` method and customized using the `setSeparator()` method.

```php
use Charcoal\Config\GenericConfig as Config;

$cfg = new Config();
$cfg->setSeparator('/');
$cfg->setData([
    'database' => [
        'params' => [
            'name' => 'mydb',
            'user' => 'myname',
            'pass' => 'secret',
        ]
    ]
]);

echo $cfg['database/params/name']; // "mydb"
```

Check out the [documentation](docs/separator-lookup.md) for more information.



### Delegates Lookup

Delegates allow several objects to share values and act as fallbacks when the current object cannot resolve a given data key.

In Config objects, _delegate objects_ are regsitered to an internal stack. If a data key cannot be resolved, the Config iterates over each delegate in the stack and stops on
the first match containing a value that is not `NULL`.

```php
use Charcoal\Config\GenericConfig as Config;

$cfg = new Config([
    'driver' => null,
    'host'   => 'localhost',
]);
$delegate = new Config([
    'driver' => 'pdo_mysql',
    'host'   => 'example.com',
    'port'   => 11211,
]);

$cfg->addDelegate($delegate);

echo $cfg['driver']; // "pdo_mysql"
echo $cfg['host']; // "localhost"
echo $cfg['port']; // 11211
```

Check out the [documentation](docs/delegates-lookup.md) for more information.



### Array Access

The Entity object implements the `ArrayAccess` interface and therefore can be used with array style:

```php
$cfg = new \Charcoal\Config\GenericConfig();

// Assigns a value to "foobar"
$cfg['foobar'] = 42;

// Returns 42
echo $cfg['foobar'];

// Returns TRUE
isset($cfg['foobar']);

// Returns FALSE
isset($cfg['xyzzy']);

// Invalidates the "foobar" key
unset($cfg['foobar']);
```

> 👉 A data key MUST be a string otherwise `InvalidArgumentException` is thrown.



### Interoperability

The Config object implements [PSR-11](psr-11): `Psr\Container\ContainerInterface`.

This interface exposes two methods: `get()` and `has()`. These methods are implemented by the Entity object as aliases of `ArrayAccess::offsetGet()` and `ArrayAccess::offsetExists()`.

```php
$config = new \Charcoal\Config\GenericConfig([
    'foobar' => 42
]);

// Returns 42
$config->get('foobar');

// Returns TRUE
$config->has('foobar');

// Returns FALSE
$config->has('xyzzy');
```

> 👉 A call to the `get()` method with a non-existing key DOES NOT throw an exception.



### Configurable Objects

Also provided in this package is a _Configurable_ mixin:

-   `Charcoal\Config\ConfigrableInterface`
-   `Charcoal\Config\ConfigurableTrait`

Configurable objects (which could have been called "_Config Aware_") can have an associated Config object that can help define various properties, states, or other.

The Config object can be assigned with `setConfig()` and retrieved with `config()`.

An added benefit of `ConfigurableTrait` is the `createConfig($data)` method which is used to create a Config object if one is not assigned. This method can be overridden in sub-classes to customize the instance returned and whatever initial state might be needed.

Check out the [documentation](docs/configurable-objects.md) for examples and more information.



## Development

To install the development environment:

```shell
$ composer install
```

To run the scripts (phplint, phpcs, and phpunit):

```shell
$ composer test
```



### API Documentation

-   The auto-generated `phpDocumentor` API documentation is available at [https://locomotivemtl.github.io/charcoal-config/docs/master/](https://locomotivemtl.github.io/charcoal-config/docs/master/)
-   The auto-generated `apigen` API documentation is available at [https://codedoc.pub/locomotivemtl/charcoal-config/master/](https://codedoc.pub/locomotivemtl/charcoal-config/master/index.html)



### Development Dependencies

-   `php-coveralls/php-coveralls`
-   `phpunit/phpunit`
-   `squizlabs/php_codesniffer`



### Coding Style

The charcoal-config module follows the Charcoal coding-style:

-   [_PSR-1_][psr-1]
-   [_PSR-2_][psr-2]
-   [_PSR-4_][psr-4], autoloading is therefore provided by _Composer_.
-   [_phpDocumentor_](http://phpdoc.org/) comments.
-   [phpcs.xml.dist](phpcs.xml.dist) and [.editorconfig](.editorconfig) for coding standards.

> Coding style validation / enforcement can be performed with `composer phpcs`. An auto-fixer is also available with `composer phpcbf`.

> This module should also throw no error when running `phpstan analyse -l7 src/` 👍.



## Credits

-   [Mathieu Ducharme](https://github.com/mducharme)
-   [Locomotive](https://locomotive.ca/)



## License

Charcoal is licensed under the MIT license. See [LICENSE](LICENSE) for details.



[charcoal-app]:       https://packagist.org/packages/locomotivemtl/charcoal-app
[charcoal-config]:    https://packagist.org/packages/locomotivemtl/charcoal-config

[dev-scrutinizer]:    https://scrutinizer-ci.com/g/locomotivemtl/charcoal-config/
[dev-coveralls]:      https://coveralls.io/r/locomotivemtl/charcoal-config
[dev-sensiolabs]:     https://insight.sensiolabs.com/projects/27ad205f-4208-4fa6-9dcf-534b3a1c0aaa
[dev-travis]:         https://travis-ci.org/locomotivemtl/charcoal-config

[badge-license]:      https://img.shields.io/packagist/l/locomotivemtl/charcoal-config.svg?style=flat-square
[badge-version]:      https://img.shields.io/packagist/v/locomotivemtl/charcoal-config.svg?style=flat-square
[badge-scrutinizer]:  https://img.shields.io/scrutinizer/g/locomotivemtl/charcoal-config.svg?style=flat-square
[badge-coveralls]:    https://img.shields.io/coveralls/locomotivemtl/charcoal-config.svg?style=flat-square
[badge-sensiolabs]:   https://img.shields.io/sensiolabs/i/27ad205f-4208-4fa6-9dcf-534b3a1c0aaa.svg?style=flat-square
[badge-travis]:       https://img.shields.io/travis/locomotivemtl/charcoal-config.svg?style=flat-square

[psr-1]:  https://www.php-fig.org/psr/psr-1/
[psr-2]:  https://www.php-fig.org/psr/psr-2/
[psr-4]:  https://www.php-fig.org/psr/psr-4/
[psr-11]: https://www.php-fig.org/psr/psr-11/
