<?php
namespace envtesting;
/**
 * Copy & Paste example
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
//if (file_exists(__DIR__ . '/Envtesting.php')) require_once __DIR__ . '/Envtesting.php';
//if (file_exists(__DIR__ . '/../Envtesting.php')) require_once __DIR__ . '/../Envtesting.php';

require_once __DIR__ . '/../envtesting/Autoloader.php';

Suite::instance('Copy & Paste example'); // change Suite name
Suite::instance()->failGroupOnFirstError(); // fail whole group on first error

// add some tests here

$mysql = Suite::instance()->mysql;
/** @var Suite $mysl */

$mysql->addTest('PDO', __DIR__ . '/../envtests/library/Pdo.php', 'library');
$mysql->addTest('PDO', __DIR__ . '/../envtests/library/Pdo.php', 'library');

// output
echo Suite::instance();