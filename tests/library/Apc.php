<?php
namespace tests\library;

/**
 * Check if APC library is loaded
 *
 * @see http://php.net/manual/en/book.apc.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('apc', 'apc_inc'),
	'APC not found'
);