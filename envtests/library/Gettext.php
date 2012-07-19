<?php
namespace envtests\library;

/**
 * Check Gettext is loaded
 *
 * @see http://php.net/manual/en/book.gettext.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::lib('gettext', 'gettext'),
	'Gettext library not found'
);

echo 'Gettext found';