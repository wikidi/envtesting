<?php
/**
 * @author Roman Ozana <roman@wikidi.com>
 */
require_once __DIR__ . '/../Envtesting.php';

$suite = new \envtesting\Suite('Super group test');

// ---------------------------------------------------------------------------------------------------------------------
// organize tests to groups
// ---------------------------------------------------------------------------------------------------------------------

// group 1
$suite->group1->addTest('APC', 'envtests/library/Apc.php')->setType('library')->setNotice('1/3');
$suite->group1->addTest('GD', 'envtests/library/Gd.php')->setType('library')->setNotice('2/3');
$suite->group1->addTest('Gettext', 'envtests/library/Gettext.php')->setType('library')->setNotice('3/3');

// group 2
$suite->group2->addTest(
	'PDO', function() {
		throw new \envtesting\Error('Die with me');
	}
)->setType('library')->setNotice('1/3');
$suite->group2->addTest('PDO', 'envtests/library/Pdo.php')->setType('library')->setNotice('2/3');
$suite->group2->addTest('Mongo', 'envtests/library/Mongo.php')->setType('library')->setNotice('3/3');

// ---------------------------------------------------------------------------------------------------------------------
// fail (not run) all tests in group when on first error
// ---------------------------------------------------------------------------------------------------------------------

echo $suite->setName('Group die')->failGroupOnFirstError()->run(); // fail group on first error

// ---------------------------------------------------------------------------------------------------------------------
// shuffle
// ---------------------------------------------------------------------------------------------------------------------

$suite->failGroupOnFirstError(false); // return fail group back

echo $suite->setName('shuffle groups')->shuffle()->run(); // group mix
echo $suite->setName('deep shuffle')->shuffle(true)->run(); // deep mix



