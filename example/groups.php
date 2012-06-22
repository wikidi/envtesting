<?php
/**
 * @author Roman Ozana <roman@wikidi.com>
 */
require_once __DIR__ . '/../Envtesting.php';

use \envtesting\Check;
use \envtesting\SuitGroup;
use \envtesting\Tests;

$group = new SuitGroup();

// add APC library test
$suit = $group->addSuit('APC');
$suit->addTest('APC', Check::file('tests/library/Apc.php'))->setType('library');

$suit = $group->addSuit('GD and Gettext');
$suit->addTest('GD', Check::file('tests/library/Gd.php'))->setType('library');
$suit->addTest('Gettext', Check::file('tests/library/Gettext.php'))->setType('library');

$suit = $group->addSuit('Mongo and PDO');
$suit->addTest('Mongo', Check::file('tests/library/Mongo.php'))->setType('library');
$suit->addTest('Pdo', Check::file('tests/library/Pdo.php'))->setType('library');

// shuffle group and return response as string
echo $group->shuffle()->run();