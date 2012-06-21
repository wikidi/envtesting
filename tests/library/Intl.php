<?php
namespace envtesting\tests\library;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check Internationalization Functions is loaded
 *
 * @see http://php.net/manual/en/book.intl.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('intl', 'intl_error_name'),
	'intl library not found'
);