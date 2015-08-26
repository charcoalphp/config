Charcoal Config
===============

Configuration container for all things Charcoal.

[![Build Status](https://travis-ci.org/locomotivemtl/charcoal-config.svg?branch=master)](https://travis-ci.org/locomotivemtl/charcoal-config)

This package provides easy hierarchical configuration container (for config storage and access). 
`Charcoal\Config` acts as a configuration registry / repository.

## Supported file formats
There are currently 3 supported file formats: `ini`, `json` and `php`.
To load configuration from a file:
```php
$config = new \Charcoal\GenericConfig();
$config->add_file('./config/my-config.json');
```

## How to use
```php
$config = new \Charcoal\GenericConfig();
$config->set('foo', [
	'baz'=>example,
	'bar'=>42
]);
// Ouput "42"
echo $config->get('foo.bar');
```

Usage with `ArrayAccess`, and setting data from the constructor
```php
$config = new \Charcoal\GenericConfig([
    'foo' => [
        'baz'=>'example',
        'bar'=>42
    ]
]);
// Output "example"
echo $config['foo/baz'];
```

Note that the previous example uses the default separator, which is `/`.
To use a different separator (for dot notation, for example) use:
```php
$config->set_separator('.');
```

## Configuration chaining

## Interoperability
The `\Charcoal\Config` container implements the `container-interop` interface.
See [https://github.com/container-interop/container-interop]

## Changelog

### 0.1.1
_Unreleased_
- Fix typo in class name

### 0.1
_Released on 2015-08-25_
- Initial release of `charcoal-config`, 
