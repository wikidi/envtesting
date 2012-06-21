<?php
namespace envtesting\tests\library;
require_once __DIR__ . '/../../Envtesting.php';

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

