<?php
/**
 * @author Roman Ozana <roman@wikidi.com>
 */
require_once __DIR__ . '/../Envtesting.php';

$tests = new \envtesting\Tests('Super group test');

// group 1
$tests->group1->addTest('APC', 'tests/library/Apc.php')->setType('library')->setNotice('1 : 1');
$tests->group1->addTest('GD', 'tests/library/Gd.php')->setType('library')->setNotice('1 : 2');
$tests->group1->addTest('Gettext', 'tests/library/Gettext.php')->setType('library')->setNotice('1 : 3');

// group 2
$tests->group2->addTest('PDO', 'tests/library/Pdo.php')->setType('library')->setNotice('2 : 1');
$tests->group2->addTest('Mongo', 'tests/library/Mongo.php')->setType('library')->setNotice('2 : 2');

echo $tests->shuffle()->run(); // group mix

echo $tests->shuffle(true)->run(); // deep mix