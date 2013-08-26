<?php
namespace envtesting;
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * @author Roman Ozana <roman@wikidi.com>
 */

$suite = new Suite('Using PHP files for test');

// ---------------------------------------------------------------------------------------------------------------------
// Even simple require_once file test
// ---------------------------------------------------------------------------------------------------------------------

$suite->addTest('APC', 'envtests/library/Apc.php', 'apc');
$suite->addTest('APC2', 'envtests/library/Apc.php', 'apc');
$suite->addTest('bzip2', 'envtests/library/Bzip2.php');
$suite->addTest('curl', 'envtests/library/Curl.php');
$suite->addTest('gd', 'envtests/library/Gd.php');
$suite->addTest('warning', 'envtests/library/Warning.php');
$suite->addTest('error', 'envtests/library/Error.php');

echo $suite->shuffle()->run(); // randomize test oreder

// ---------------------------------------------------------------------------------------------------------------------
// print tests result yourself
// ---------------------------------------------------------------------------------------------------------------------

foreach ($suite as $tests) {
	foreach ($tests as $test/** @var \envtesting\Test $test */) {
		echo ($test->isOk() ? '✓' : '☠') . ' ' . $test->getName() . PHP_EOL; // print resuly yourself
	}
}
