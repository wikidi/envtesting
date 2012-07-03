# envtesting

Fast simple and easy to use environment testing written in PHP. Can check library, services and services response.
Produce console, HTML or CSV output.

## Example

```php
<?php
require_once __DIR__ . '/Envtesting.php'; // just one file !!!

$suit = new \envtesting\Suit();
$suit->addTest('APC', 'tests/library/Apc.php', 'apc'); // from our sources
$suit->addTest('SOMETHING', function() { if (true === false) throw new \envtesting\Error('True === false'); }, 'bool');
```

Visit more examples in: https://github.com/wikidi/envtesting/tree/master/example

## Requirments

- PHP 5.3 +

