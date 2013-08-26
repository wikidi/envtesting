<?php
namespace envtesting;
/**
 * Example how to test Memcached connection
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

$suite = new \envtesting\Suite('memcached');
$suite->addTest('memcache', new \envtests\services\memcache\Connection('127.0.0.1', 11211), 'service'); // KISS
$suite->run()->render();