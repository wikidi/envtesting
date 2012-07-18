<?php
namespace envtesting;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
Throws::allErrors(); // throw WARNING and NOTICE as Exception

$filter = Filter::instanceFromArray($_GET);
$suite = Suite::instance('My Suite', $filter)->failGroupOnFirstError();

// ---------------------------------------------------------------------------------------------------------------------
// memcache
// ---------------------------------------------------------------------------------------------------------------------

$memcache = $suite->memcache;
/** @var Suit $memcache */

$memcache->addTest('Memcache', App::$root . '/envtests/library/Memcache.php', 'library');
$memcache->addTest(
	'APP Memcache', new \envtests\application\memcache\Operations('127.0.0.1', 11211), 'application'
)->setNotice('server');


// ---------------------------------------------------------------------------------------------------------------------
// mongo
// ---------------------------------------------------------------------------------------------------------------------
$dsn = 'mongodb://localhost:27017/testomato'; // can be read from your config;
$options = array();

$mongo = $suite->mongo;
/** @var Suit $mongo */
$mongo->addTest('Mongo', App::$root . '/envtests/library/Mongo.php', 'library');
$mongo->addTest('mongo:connect', new \envtests\services\mongo\Connection($dsn, $options), 'service');
$mongo->addTest('mongo:operations', new \envtests\application\mongo\Operations($dsn, $options), 'application');


// ---------------------------------------------------------------------------------------------------------------------
// mysql
// ---------------------------------------------------------------------------------------------------------------------

//$mysql = $suite->mysql;
/** @var Suit $mysql */

//$mysql->addTest('PDO', App::$root . '/envtests/library/Pdo.php', 'library');

// check master operations
//$masterOperations = new \envtests\application\mysql\Operations($dsn);
//$mysql->addTest('master:connect', $mc = new \envtests\services\mysql\Connection($dsn), 'service')->setNotice('master');
//$mysql->addTest('mysql:insert', array($masterOperations, 'insertAllow'), 'application')->setNotice('master');
//$mysql->addTest('mysql:delete', array($masterOperations, 'deleteAllow'), 'application')->setNotice('master');
//$mysql->addTest('mysql:select', array($masterOperations, 'selectAllow'), 'application')->setNotice('master');
//$mysql->addTest('mysql:update', array($masterOperations, 'updateAllow'), 'application')->setNotice('master');

// check slave operations
//$mysql->addTest('slave:connect', new \envtests\services\mysql\Connection($dsn), 'service')->setNotice('slave');
//$slaveOperations = new \envtests\application\mysql\Operations($dsn);
//$mysql->addTest('mysql:select', array($slaveOperations, 'selectAllow'), 'application')->setNotice('slave');
//$mysql->addTest('mysql:insert', array($slaveOperations, 'insertNotAllow'), 'application')->setNotice('slave');
//$mysql->addTest('mysql:delete', array($slaveOperations, 'deleteNotAllow'), 'application')->setNotice('slave');
//$mysql->addTest('mysql:update', array($slaveOperations, 'updateNotAllow'), 'application')->setNotice('slave');

Throws::nothing(); // return errors BACK to PHP

echo $suite->run()->render();