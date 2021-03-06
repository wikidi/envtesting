<?php
namespace envtests\library;

/**
 * Check if Sphinx Client is supported
 *
 * @see http://php.net/manual/en/book.sphinx.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::cls('SphinxClient'),
	'SphinxClient class not found'
);

