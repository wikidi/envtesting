<?php
require_once __DIR__ . '/../Envtesting.php';

use \envtesting\Check;
use \envtesting\TestSuit;

/**
 * @author Roman Ozana <roman@wikidi.com>
 */

$suit = new TestSuit('Using PHP files for test');

/* ------------------------------------------------------------------------- *
 * Even simple require_once file test
 * ------------------------------------------------------------------------- */

$suit->addTest('APC', Check::file('tests/library/Apc.php'), 'apc')->setType('library');
$suit->addTest('XXX', Check::file('tests/library/Apc.php'), 'apc')->setType('library');

$suit->addTest('bzip2', Check::file('tests/library/Bzip2.php'))->setType('library');
$suit->addTest('x', Check::file('tests/library/Curl.php'))->setType('library');
$suit->addTest('a', Check::file('tests/library/Curl.php'), 'n')->setType('library');
$suit->addTest('b', Check::file('tests/library/Curl.php'), 'n')->setType('library');
$suit->addTest('c', Check::file('tests/library/Curl.php'), 'n')->setType('library');

$suit->shuffle();

foreach ($suit as $name => $groups) {
	echo '---' . $name . ' --- ' . PHP_EOL;
	foreach ($groups as $f => $test/** @var \envtesting\Test $test */) {
		echo $test->getName() . PHP_EOL;
	}
}

//echo $suit->run();
