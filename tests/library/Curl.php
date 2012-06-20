<?php
namespace envtesting\tests;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check if curl library is loaded
 *
 * @see http://php.net/manual/en/book.curl.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('curl', 'curl_init'),
	'Curl not found'
);