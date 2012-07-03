# envtesting

Fast simple and easy to use environment testing written in PHP. Can check library, services and services response.
Produce console, HTML or CSV output.

## Example

```php
<?php
require_once __DIR__ . '/Envtesting.php'; // just one file !!!

$suit = new \envtesting\Suit();

$suit->addTest('APC', 'tests/library/Apc.php', 'apc'); // by file

$suit->addTest('SOMETHING', function() {
	if (true === false) throw new \envtesting\Error('True === false'); // by anonymous function
	}, 'boolean');

$suit->addTest('callback', array($test, 'perform_test'), 'callback'); // by callback

$suit->addTest('callback', '', 'call'); // by callback

$suit->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service'); // new object with _invoke

```

Visit more examples in: https://github.com/wikidi/envtesting/tree/master/example

## Requirments

- PHP 5.3 +

