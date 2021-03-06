<?php
namespace envtests\library;

/**
 * Check Zlib Compression support
 *
 * @see http://cz2.php.net/manual/en/book.zlib.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('zlib', 'gzopen'),
	'zlib library not found'
);