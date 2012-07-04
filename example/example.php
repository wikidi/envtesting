<?php
require_once __DIR__ . '/../Envtesting.php';

// ---------------------------------------------------------------------------------------------------------------------
// Function callback
// ---------------------------------------------------------------------------------------------------------------------

$suit = new \envtesting\Suit('Function callback');
function apcRequireTest() {
	require_once 'tests/library/Apc.php';
}

$suit->addTest('APC', 'apcRequireTest', 'library');
echo $suit->run();

// ---------------------------------------------------------------------------------------------------------------------
// Static class callback
// ---------------------------------------------------------------------------------------------------------------------

abstract class TestCollection {
	public static function apcRequireTest() {
		require_once 'tests/library/Apc.php';
	}
}

$suit = new \envtesting\Suit('Static class callback');
$suit->addTest('static', '\TestCollection::apcRequireTest', 'library');
echo $suit->run();


// ---------------------------------------------------------------------------------------------------------------------
// Lambda function
// ---------------------------------------------------------------------------------------------------------------------

$suit = new \envtesting\Suit('Lambda function');

$suit->addTest('lambda1', function() { return 'YOU'; }, 'lib');

$suit->addTest(
	'lambda2', function() {
		throw new \envtesting\Error('This is SPARTA !!!');
	}, 'lib'
);

$suit->addTest(
	'lambda3', function() {
		throw new \envtesting\Warning('Nooooooooooooooooo!');
	}, 'lib'
);

$suit->addTest(
	'lambda4', function() {
		throw new \Exception('Star Wars Kid attacking');
	}, 'lib'
);

echo $suit->run();

// ---------------------------------------------------------------------------------------------------------------------
// Invoke
// ---------------------------------------------------------------------------------------------------------------------

$suit = new \envtesting\Suit('Class with invoke');
$suit->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service'); // KISS
echo $suit->run();