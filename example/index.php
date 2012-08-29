<?php
namespace envtesting;
/**
 * Copy & Paste example
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php'; // or
// require_once __DIR__ . '/Envtesting.php'; // depends on your structure

Throws::allErrors(); // throws everything (warnings, notices, fatals) as reular Exceptions

Suite::instance('Copy & Paste example'); // change Suite name
Suite::instance()->failGroupOnFirstError(); // fail whole group on first error

// add some tests here

$mysql = Suite::instance()->mysql; // create new Group
/** @var Suite $mysl */

$mysql->addTest('PDO', __DIR__ . '/../envtests/library/Pdo.php', 'library');

// create another group
$apc = Suite::instance()->apc;
/** @var Suite $apc */
$apc->addTest('APC', __DIR__ . '/../envtests/library/Apc.php', 'library');


echo Suite::instance()->run(); // or
// Suite::instance()->run()->render(); // for getting HTML and CSV output

Throws::nothing();