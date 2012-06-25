<?php
require_once __DIR__ . '/../Envtesting.php';

use envtesting\Tests;
use envtesting\Check;

/**
 * Autoload all PHP tests from directory
 *
 * @author Roman Ozana <roman@omdesign.cz>
 */

// run tests and show result
echo $tests = Tests::instance('All libs autoloaded')->addFromDir(__DIR__ . '/../tests/library', 'library')->run();

// change fitst test
$tests[0] = new \envtesting\Test('NEW TEST', function() {
	throw new \envtesting\Error('Black Hawk Down');
});

// ---------------------------------------------------------------------------------------------------------------------
// test unseting
// ---------------------------------------------------------------------------------------------------------------------

// unset something
unset($tests[1]);
unset($tests[2]);
unset($tests[3]);

// ---------------------------------------------------------------------------------------------------------------------
// repeat test execution
// ---------------------------------------------------------------------------------------------------------------------

// shuffle and run all tests again
echo $tests->shuffle()->run();

// ---------------------------------------------------------------------------------------------------------------------
// group example
// ---------------------------------------------------------------------------------------------------------------------

$group = Tests::instance('Groups');

// autoload tests to group
$group->to('Autoloaded dir')->addFromDir(__DIR__ . '/../tests/library', 'something');

// add tests to new Group
$group->newGroup->addTest('APC', Check::file('tests/library/Apc.php'), 'apc');
$group->newGroup->addTest('GETTEXT', Check::file('tests/library/Gettext.php'), 'apc');

$group->shuffle(false);

echo $group->run();