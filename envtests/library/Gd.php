<?php
namespace envtests\library;

/**
 * Check GD library is loaded
 *
 * @see http://php.net/manual/en/book.image.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('gd', 'gd_info'),
	'GD library not found'
);