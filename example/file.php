<?php
require_once __DIR__ . '/../Envtesting.php';

use \envtesting\Check;
use \envtesting\Suit;

/**
 * @author Roman Ozana <roman@wikidi.com>
 */

$suit = new Suit('Using PHP files for test');

/* ------------------------------------------------------------------------- *
 * Even simple require_once file test
 * ------------------------------------------------------------------------- */

$suit->addTest('APC', 'tests/library/Apc.php', 'apc');
$suit->addTest('APC2', 'tests/library/Apc.php', 'apc');
$suit->addTest('bzip2', 'tests/library/Bzip2.php');
$suit->addTest('curl', 'tests/library/Curl.php');
$suit->addTest('gd', 'tests/library/Gd.php');
$suit->addTest('warning', 'tests/library/Warning.php');
$suit->addTest('error', 'tests/library/Error.php');

echo $suit->shuffle(); // randomize test oreder

die('TODO TODO TODO TODO');

foreach ($suit->get() as $test/** @var \envtesting\Test $test */) {
	echo ($test->isOk() ? '✓' : '☠') . ' ' . $test->getName() . PHP_EOL; // print resuly yourself
}

//echo $suit->run(); // or print it in our format
