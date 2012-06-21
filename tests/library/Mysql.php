<?php
namespace envtesting\tests\library;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check if Original MySQL API is loaded
 *
 * @see http://php.net/manual/en/book.mysql.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('mysql', 'mysql_info'),
	'mysql library not found'
);