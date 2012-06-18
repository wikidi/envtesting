<?php
require_once __DIR__ . '/../Envtesting.php';

use \envtesting\Check;
use \envtesting\TestSuit;

/* ------------------------------------------------------------------------- *
 * Create new test suit
 * ------------------------------------------------------------------------- */

$suit = new TestSuit();

/* ------------------------------------------------------------------------- *
 * Callback tests
 * ------------------------------------------------------------------------- */

function apcRequireTest() {
	require_once 'tests/library/Apc.php';
}

$suit->call('apcRequireTest', 'APC', 'callback library test');

class TestCollection {
	public static function apcRequireTest() {
		require_once 'tests/library/Apc.php';
	}
}

$suit->call(array('\TestCollection', 'apcRequireTest'), 'APC', 'library testCollection');

/* ------------------------------------------------------------------------- *
 * Lambda function
 * ------------------------------------------------------------------------- */

$suit->call(
	function() {
		throw new \envtesting\Error('not working at all');
	}, 'working', 'library'
);

$suit->call(
	function() {
		throw new \envtesting\Warning('Something wrong');
	}, 'AAA', 'library'
);

/* ------------------------------------------------------------------------- *
 * Even simple require_once file test
 * ------------------------------------------------------------------------- */
$suit->call(Check::useFile('tests/library/Apc.php'), 'APC', 'library');
$suit->call(Check::useFile('tests/library/Bzip2.php'), 'bzip2', 'library');
$suit->call(Check::useFile('tests/library/Curl.php'), 'curl', 'library');


/* ------------------------------------------------------------------------- *
 * Executing
 * ------------------------------------------------------------------------- */

echo $suit->shuffle()->run();

/* ------------------------------------------------------------------------- *
 * Array access
 * ------------------------------------------------------------------------- */

foreach ($suit as $test/** @var \envtesting\Test $test*/) {
	try {
		$test->run(); // run test
	} catch (\envtesting\Error $e) {

	} catch (\envtesting\Warning $w) {

	}

	echo $test . PHP_EOL;
}
