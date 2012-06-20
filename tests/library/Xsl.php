<?php
namespace envtesting\tests;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check XSL support
 *
 * @see http://cz2.php.net/manual/en/book.xsl.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('xsl'),
	'XLS library not found'
);