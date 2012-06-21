<?php
require_once __DIR__ . '/../Envtesting.php';

use \envtesting\Check;
use \envtesting\TestSuit;

/**
 * @author Roman Ozana <roman@wikidi.com>
 */

$suit = new TestSuit('Using PHP files for test');

/* ------------------------------------------------------------------------- *
 * Even simple require_once file test
 * ------------------------------------------------------------------------- */

$suit->addTest('APC', Check::file('tests/library/Apc.php'), 'apc');
$suit->addTest('APC2', Check::file('tests/library/Apc.php'), 'apc');
$suit->addTest('bzip2', Check::file('tests/library/Bzip2.php'));
$suit->addTest('curl', Check::file('tests/library/Curl.php'));
$suit->addTest('gd', Check::file('tests/library/Gd.php'));
$suit->addTest('warning', Check::file('tests/library/Warning.php'));
$suit->addTest('error', Check::file('tests/library/Error.php'));

$suit->shuffle(); // randomize test oreder

foreach ($suit as $test/** @var \envtesting\Test $test */) {
	echo ($test->isOk() ? '✓' : '☠') . ' ' . $test->getName() . PHP_EOL; // print resuly yourself
}

//echo $suit->run(); // or print it in our format
