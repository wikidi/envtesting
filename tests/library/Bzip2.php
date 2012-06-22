<?php
namespace tests\library;

/**
 * Check if bz2 library is loaded
 *
 * @see http://php.net/manual/en/book.bzip2.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('bz2', 'bzopen'),
	'Bzip not found'
);