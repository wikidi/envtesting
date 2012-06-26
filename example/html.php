<?php
/**
 * Example how to test Memcached connection and return HTML output
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';

use envtesting\output\Html;
use envtesting\Suit;

$suit = new Suit('memcached');
$suit->addTest('memcache', new \tests\services\MemcacheConnection('127.0.0.1', 11211), 'service'); // KISS

Html::render($suit, 'Envtesting output as HTML'); // KISS
