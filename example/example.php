<?php
require_once __DIR__ . '/../Envtesting.php';

use \envtesting\Check;
use \envtesting\Tests;


/* ------------------------------------------------------------------------- *
 * Create new test suit
 * ------------------------------------------------------------------------- */

$suit = new Tests('Example test suit');
function apcRequireTest() { require_once 'tests/library/Apc.php'; }
$suit->addTest('APC', 'apcRequireTest', 'library');


echo $suit;
die();

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
