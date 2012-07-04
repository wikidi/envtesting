<?php
/**
 * Example how to test Memcached connection
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';
require_once __DIR__ . '/../tests/services/MemcacheConnection.php';

$suit = new \envtesting\Suit('memcached');
$suit->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service'); // KISS
echo $suit->run();