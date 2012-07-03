# envtesting

Fast simple and easy to use environment testing written in PHP. Can check library, services and services response.
Produce console, HTML or CSV output.

## How to

Envtesting provide **multiple way** how to test something. You can test:

- using regular PHP file
- using [anonymous function](http://php.net/manual/en/functions.anonymous.php)
- using static or regular "callback"(http://php.net/manual/en/language.types.callable.php)
- using object with [__invoke function](http://www.php.net/manual/en/language.oop5.magic.php#object.invoke)


```php
<?php
$suit = new \envtesting\Suit('my great envtest');
$suit->addTest('APC', 'tests/library/Apc.php', 'apc'); // by file
```

Using anonymous function:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suit = new \envtesting\Suit('my great envtest');
$suit->addTest('SOMETHING', function() {
	if (true === false) throw new \envtesting\Error('True === false');
	}, 'boolean');
```

Usin regular callback:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suit = new \envtesting\Suit('my great envtest');
$suit->addTest('callback', array($test, 'perform_test'), 'callback');
```
Using static callback string:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suit = new \envtesting\Suit('my great envtest');
$suit->addTest('callback', '\we\can\call\Something::static', 'call');
```

Test using object with __invoke:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suit = new \envtesting\Suit('my great envtest');
$suit->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service');
```

Tests can be grouped into group:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suit = new \envtesting\Suit('my great envtest');
$suit->group->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service');
$suit->group2->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service');
```

You can shuffle groups of tests inside groups:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suit = new \envtesting\Suit('my great envtest');
$suit->group->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service');
$suit->group->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service');
$suit->group2->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service');
$suit->shuffle(); // mixe only groups
$suit->shuffle(true); // deep shuffle mix groups and tests inside group
```
Envtesting can render CSV, HTML or text output:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suit = new \envtesting\Suit('my great envtest');
echo $suit; // produce TXT output
$suit->render('csv'); // render CSV output
$suit->render('html'); // render HTML output
```


Visit more examples in: https://github.com/wikidi/envtesting/tree/master/example

## Requirments

- PHP 5.3 +

## Meta

Author: [wikidi.com](http://wikidi.com) & [Roman Ožana](https://github.com/OzzyCzech)

For the license terms see LICENSE.TXT files.
