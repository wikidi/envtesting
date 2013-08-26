# envtesting

Fast simple and easy to use environment testing written in PHP. Can check library, services and services response.
Produce console, HTML or CSV output.

![HTML output example](/wikidi/envtesting/raw/master/doc/envtesting.png "HTML output")

## How to use

1. copy [Envtesting.php](https://raw.github.com/wikidi/envtesting/master/doc/envtesting.php) and folder [envtests](https://github.com/wikidi/envtesting/tree/master/envtests)
2. create index.php and require Envtesting.php
3. add some test to Suite
4. return output as html/csv/txt

## Envtesting advantages

Envtesting provide **multiple way** how to test something. You can test:

- using regular PHP file
- using [anonymous function](http://php.net/manual/en/functions.anonymous.php)
- using static or regular [callback](http://php.net/manual/en/language.types.callable.php)
- using object with [__invoke function](http://www.php.net/manual/en/language.oop5.magic.php#object.invoke)


```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suite = new \envtesting\Suite('my great envtest');
$suite->addTest('APC', 'envtests/library/Apc.php', 'apc'); // by file
echo $suite;
```
or using singelton instance of Suite

```php
<?php
require_once __DIR__ . '/Envtesting.php';
Suite::instance()->addTest('APC', 'envtests/library/Apc.php', 'apc'); // by file
echo Suite::instance();
```

Using anonymous function:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suite = new \envtesting\Suite('my great envtest');
$suite->addTest('SOMETHING', function() {
	if (true === false) throw new \envtesting\Error('True === false');
	}, 'boolean');
```

Using regular callback:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suite = new \envtesting\Suite('my great envtest');
$suite->addTest('callback', array($test, 'perform_test'), 'callback');
```
Using static callback string:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suite = new \envtesting\Suite('my great envtest');
$suite->addTest('callback', '\we\can\call\Something::static', 'call');
```

Test using object with __invoke:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suite = new \envtesting\Suite('my great envtest');
$suite->addTest('memcache', new \envtests\services\memcache\Connection('127.0.0.1', 11211), 'service');
```

Tests can be grouped into group:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suite = new \envtesting\Suite('my great envtest');
$suite->group->addTest('memcache', new \envtests\services\memcache\Connection('127.0.0.1', 11211), 'service');
$suite->group2->addTest('memcache', new \envtests\services\memcache\Connection('127.0.0.1', 11211), 'service');
```

You can shuffle groups of shuffle tests inside groups:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suite = new \envtesting\Suite('my great envtest');
$suite->group->addTest('memcache', new \envtests\services\memcache\Connection('127.0.0.1', 11211), 'service');
$suite->group->addTest('memcache', new \envtests\services\memcache\Connection('127.0.0.1', 11211), 'service');
$suite->group2->addTest('memcache', new \envtests\services\memcache\Connection('127.0.0.1', 11211), 'service');
$suite->shuffle(); // mix only groups
$suite->shuffle(true); // deep shuffle mix groups and tests inside group
```
Envtesting can render CSV, HTML or text output:

```php
<?php
require_once __DIR__ . '/Envtesting.php';
$suite = new \envtesting\Suite('my great envtest');
echo $suite;            // generate TXT output
$suite->render('csv');  // render CSV output
$suite->render('html'); // render HTML output
```

Visit more examples in: https://github.com/wikidi/envtesting/tree/master/example

## Requirments

- PHP 5.3 +

## Update

```
npm intall         # install all NPM deps
composer install   # install Apigen

# reelase
grunt release      # update api docs, minify and release minor version

# or one by one
grunt minify       # minify (shrink) PHP file
grunt doc          # generate Apigen docs
grunt bump         # release minor version

# or release major version

grunt bump:major   # release major version

```

- PHP 5.3 +

## Media

- http://www.zdrojak.cz/clanky/envtesting-overujeme-nastaveni-prostredi/

## Meta

Author: [wikidi.com](http://wikidi.com) & [Roman Ožana](https://github.com/OzzyCzech)

For the license terms see LICENSE.TXT files.
