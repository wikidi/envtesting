<?php
require_once __DIR__ . '/../Envtesting.php';

use envtesting\Suite;
use envtesting\Check;

/**
 * Autoload all PHP tests from directory
 *
 * @author Roman Ozana <roman@omdesign.cz>
 */

// run tests and show result
echo $suite = Suite::instance('All libs autoloaded')->addFromDir(__DIR__ . '/../envtests/library', 'library')->run();

// change fitst test
$suite[0] = new \envtesting\Test('NEW TEST', function() {
	throw new \envtesting\Error('Black Hawk Down');
});

// ---------------------------------------------------------------------------------------------------------------------
// test unseting
// ---------------------------------------------------------------------------------------------------------------------

// unset something
unset($suite[1]);
unset($suite[2]);
unset($suite[3]);

// ---------------------------------------------------------------------------------------------------------------------
// repeat test execution
// ---------------------------------------------------------------------------------------------------------------------

// shuffle and run all tests again
echo $suite->shuffle()->run();

// ---------------------------------------------------------------------------------------------------------------------
// group example
// ---------------------------------------------------------------------------------------------------------------------

$group = Suite::instance('Groups');

// autoload tests to group
$group->to('Autoloaded dir')->addFromDir(__DIR__ . '/../envtests/library', 'something');

// add tests to new Group
$group->newGroup->addTest('APC', Check::file('envtests/library/Apc.php'), 'apc');
$group->newGroup->addTest('GETTEXT', Check::file('envtests/library/Gettext.php'), 'apc');

$group->shuffle(false);

echo $group->run();