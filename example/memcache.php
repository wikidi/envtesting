<?php
/**
 * Example how to test Memcached connection
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';

$suite = new \envtesting\Suite('memcached');
$suite->addTest('memcache', new \envtests\services\memcache\Connection('127.0.0.1', 11211), 'service'); // KISS
echo $suite->run();