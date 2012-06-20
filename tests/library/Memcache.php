<?php
namespace envtesting\tests;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check if memcache is loaded
 *
 * @see http://php.net/manual/en/book.memcache.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('memcache'),
	'memcache library not found'
);