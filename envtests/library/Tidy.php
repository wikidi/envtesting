<?php
namespace envtests\library;

/**
 * Check if tidy is avaliable
 *
 * @see http://php.net/manual/en/book.tidy.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('tidy'),
	'Tidy'
);

