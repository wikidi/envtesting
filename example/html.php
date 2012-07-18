<?php
/**
 * Example how to test Memcached connection and return HTML output
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../envtesting/Autoloader.php';

if (PHP_SAPI === 'cli') { // client
	$_SERVER['REQUEST_URI'] = 'http://example.com/envtesting/';
	$_SERVER['QUERY_STRING'] = 'type=sometype';
}

$suite = \envtesting\Suite::instance('memcached suit');
$suite->addTest('memcache', new \envtests\services\memcache\Connection('127.0.0.1', 11211), 'service'); // KISS
$suite->run()->render('html');
