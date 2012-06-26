<?php
/**
 * @author Roman Ozana <roman@wikidi.com>
 */
require_once __DIR__ . '/../Envtesting.php';

$suit = new \envtesting\Suit('Super group test');

// group 1
$suit->group1->addTest('APC', 'tests/library/Apc.php')->setType('library')->setNotice('1 : 1');
$suit->group1->addTest('GD', 'tests/library/Gd.php')->setType('library')->setNotice('1 : 2');
$suit->group1->addTest('Gettext', 'tests/library/Gettext.php')->setType('library')->setNotice('1 : 3');

// group 2
$suit->group2->addTest('PDO', 'tests/library/Pdo.php')->setType('library')->setNotice('2 : 1');
$suit->group2->addTest('Mongo', 'tests/library/Mongo.php')->setType('library')->setNotice('2 : 2');

echo $suit->shuffle()->run(); // group mix

echo $suit->shuffle(true)->run(); // deep mix