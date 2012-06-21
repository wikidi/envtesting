<?php
namespace envtesting\tests\library;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check if SimpleXml is supported
 *
 * @see http://php.net/manual/en/book.pdo.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('simplexml'),
	'Simplexml library not found'
);