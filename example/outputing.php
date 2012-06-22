<?php
/**
 * Example how to test Memcached connection and return HTML output
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';

$suit = new \envtesting\Tests('memcached');
$suit->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service'); // KISS

\envtesting\output\Html::instance('Envtesting output as HTML')->add($suit)->render();
