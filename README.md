# envtesting

Fast simple and easy to use environment testing written in PHP. Can check library, services and services response.
Produce console, HTML or CSV output.

## How to

Require just one file in your PHP

```php
<?php
require_once __DIR__ . '/Envtesting.php';
```

Create new test suit

```php
$suit = new \envtesting\Suit('my great envtest');
````
Envtesting provide multiple way how to test something. You can test:

- using regular PHP file
- using anonymous function
- using static or regular callback
- using object with __invoke

```php
$suit->addTest('APC', 'tests/library/Apc.php', 'apc'); // by file
```

```php
$suit->addTest('SOMETHING', function() {
	if (true === false) throw new \envtesting\Error('True === false'); // by anonymous function
	}, 'boolean');
```

```php
$suit->addTest('callback', array($test, 'perform_test'), 'callback'); // by callback
```

```php
$suit->addTest('callback', '\we\can\call\Something::static', 'call'); // by static callback
```

Test using object with __invoke:

```php
$suit->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service');
```

Visit more examples in: https://github.com/wikidi/envtesting/tree/master/example

## Requirments

- PHP 5.3 +

## Meta

Author: [wikidi.com](http://wikidi.com) & [Roman Ožana](https://github.com/OzzyCzech)

For the license terms see LICENSE.TXT files.
