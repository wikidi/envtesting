<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// ---------------------------------------------------------------------------------------------------------------------
// Function callback
// ---------------------------------------------------------------------------------------------------------------------

$suite = new \envtesting\Suite('Function callback');
function apcRequireTest() {
	ob_start();
	require_once __DIR__ . '/../envtests/library/Apc.php';
	return ob_get_clean();
}

$suite->addTest('APC', 'apcRequireTest', 'library');
echo $suite->run();

// ---------------------------------------------------------------------------------------------------------------------
// Static class callback
// ---------------------------------------------------------------------------------------------------------------------

abstract class TestCollection {
	public static function apcRequireTest() {
		ob_start();
		require_once __DIR__ . '/../envtests/library/Apc.php';
		return ob_get_clean();
	}
}

$suite = new \envtesting\Suite('Static class callback');
$suite->addTest('static', '\TestCollection::apcRequireTest', 'library');
echo $suite->run();


// ---------------------------------------------------------------------------------------------------------------------
// Lambda function
// ---------------------------------------------------------------------------------------------------------------------

$suite = new \envtesting\Suite('Lambda function');

$suite->addTest(
	'lambda1', function() {
		return 'YOU';
	}, 'lib'
);

$suite->addTest(
	'lambda2', function() {
		throw new \envtesting\Error('This is SPARTA !!!');
	}, 'lib'
);

$suite->addTest(
	'lambda3', function() {
		throw new \envtesting\Warning('Nooooooooooooooooo!');
	}, 'lib'
);

$suite->addTest(
	'lambda4', function() {
		throw new \Exception('Star Wars Kid attacking');
	}, 'lib'
);

echo $suite->run();

// ---------------------------------------------------------------------------------------------------------------------
// Invoke
// ---------------------------------------------------------------------------------------------------------------------

$suite = new \envtesting\Suite('Class with invoke');
$suite->addTest('memcache', new \envtests\services\memcache\Connection('127.0.0.1', 11211), 'service'); // KISS
echo $suite->run();