<?php
require_once __DIR__ . '/../Envtesting.php';

use envtesting\TestSuit;
use envtesting\SuitGroup;

/**
 * Autoload all PHP tests from directory
 *
 * @author Roman Ozana <roman@omdesign.cz>
 */

// KISS example
echo TestSuit::instance('All libs autoloaded')->fromDir(__DIR__ . '/../tests/library', 'library')->shuffle()->run();

// grou example
$group = new SuitGroup('Autoloaded tests');
$group->addSuit(
	'library',
	TestSuit::instance('one')->fromDir(__DIR__ . '/../tests/library', 'something')
);
$group->addSuit(
	'library2',
	TestSuit::instance('two suffle')->fromDir(__DIR__ . '/../tests/library', 'something')->shuffle()
);

echo $group->run();