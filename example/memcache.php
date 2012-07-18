<?php
/**
 * Example how to test Memcached connection
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';
require_once __DIR__ . '/../tests/services/memcache/Connection.php';

$suit = new \envtesting\Suite('memcached');
$suit->addTest('memcache', new \tests\services\memcache\Connection('127.0.0.1', 11211), 'service'); // KISS
echo $suit->run();