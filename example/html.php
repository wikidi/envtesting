<?php
/**
 * Example how to test Memcached connection and return HTML output
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';

$suit = \envtesting\Suit::instance('memcached suit');
$suit->addTest('memcache', new \tests\services\memcache\Connection('127.0.0.1', 11211), 'service'); // KISS
$suit->render('html');
