<?php
namespace envtesting\tests\library;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check if PHP Data Objects (PDO) exists
 *
 * @see http://php.net/manual/en/book.pdo.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::cls('PDO'),
	'PDO class not found'
);