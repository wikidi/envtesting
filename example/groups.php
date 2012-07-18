<?php
/**
 * @author Roman Ozana <roman@wikidi.com>
 */
require_once __DIR__ . '/../Envtesting.php';

$suit = new \envtesting\Suite('Super group test');

// ---------------------------------------------------------------------------------------------------------------------
// organize tests to groups
// ---------------------------------------------------------------------------------------------------------------------

// group 1
$suit->group1->addTest('APC', 'tests/library/Apc.php')->setType('library')->setNotice('1/3');
$suit->group1->addTest('GD', 'tests/library/Gd.php')->setType('library')->setNotice('2/3');
$suit->group1->addTest('Gettext', 'tests/library/Gettext.php')->setType('library')->setNotice('3/3');

// group 2
$suit->group2->addTest(
	'PDO', function() {
		throw new \envtesting\Error('Die with me');
	}
)->setType('library')->setNotice('1/3');
$suit->group2->addTest('PDO', 'tests/library/Pdo.php')->setType('library')->setNotice('2/3');
$suit->group2->addTest('Mongo', 'tests/library/Mongo.php')->setType('library')->setNotice('3/3');

// ---------------------------------------------------------------------------------------------------------------------
// fail (not run) all tests in group when on first error
// ---------------------------------------------------------------------------------------------------------------------

echo $suit->setName('Group die')->failGroupOnFirstError()->run(); // fail group on first error

// ---------------------------------------------------------------------------------------------------------------------
// shuffle
// ---------------------------------------------------------------------------------------------------------------------

$suit->failGroupOnFirstError(false); // return fail group back

echo $suit->setName('shuffle groups')->shuffle()->run(); // group mix
echo $suit->setName('deep shuffle')->shuffle(true)->run(); // deep mix



