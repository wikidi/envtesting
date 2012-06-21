<?php
namespace envtesting\tests\library;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check if MongoDB Native Driver is loaded
 *
 * @see http://php.net/manual/en/book.mongo.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::cls('MongoDB'),
	'MongoDB class not found'
);