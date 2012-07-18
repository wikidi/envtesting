<?php
require_once __DIR__ . '/../Envtesting.php';

use \envtesting\Check;
use \envtesting\Suite;

/**
 * @author Roman Ozana <roman@wikidi.com>
 */

$suit = new Suite('Using PHP files for test');

// ---------------------------------------------------------------------------------------------------------------------
// Even simple require_once file test
// ---------------------------------------------------------------------------------------------------------------------

$suit->addTest('APC', 'envtests/library/Apc.php', 'apc');
$suit->addTest('APC2', 'envtests/library/Apc.php', 'apc');
$suit->addTest('bzip2', 'envtests/library/Bzip2.php');
$suit->addTest('curl', 'envtests/library/Curl.php');
$suit->addTest('gd', 'envtests/library/Gd.php');
$suit->addTest('warning', 'envtests/library/Warning.php');
$suit->addTest('error', 'envtests/library/Error.php');

echo $suit->shuffle()->run(); // randomize test oreder

// ---------------------------------------------------------------------------------------------------------------------
// print tests result yourself
// ---------------------------------------------------------------------------------------------------------------------

foreach ($suit as $tests) {
	foreach ($tests as $test/** @var \envtesting\Test $test */) {
		echo ($test->isOk() ? '✓' : '☠') . ' ' . $test->getName() . PHP_EOL; // print resuly yourself
	}
}
