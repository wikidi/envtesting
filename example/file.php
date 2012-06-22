<?php
require_once __DIR__ . '/../Envtesting.php';

use \envtesting\Check;
use \envtesting\Tests;

/**
 * @author Roman Ozana <roman@wikidi.com>
 */

$tests = new Tests('Using PHP files for test');

/* ------------------------------------------------------------------------- *
 * Even simple require_once file test
 * ------------------------------------------------------------------------- */

$tests->addTest('APC', 'tests/library/Apc.php', 'apc');
$tests->addTest('APC2', 'tests/library/Apc.php', 'apc');
$tests->addTest('bzip2', 'tests/library/Bzip2.php');
$tests->addTest('curl', 'tests/library/Curl.php');
$tests->addTest('gd', 'tests/library/Gd.php');
$tests->addTest('warning', 'tests/library/Warning.php');
$tests->addTest('error', 'tests/library/Error.php');

echo $tests->shuffle(); // randomize test oreder

die('TODO TODO TODO TODO');

foreach ($tests->get() as $test/** @var \envtesting\Test $test */) {
	echo ($test->isOk() ? '✓' : '☠') . ' ' . $test->getName() . PHP_EOL; // print resuly yourself
}

//echo $tests->run(); // or print it in our format
