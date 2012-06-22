<?php
namespace tests\library;

/**
 * Check wordProb classes
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::cls('wikidi_webDataSearch_categorizer_wordProb_Client'),
	'Class "wikidi_webDataSearch_categorizer_wordProb_Client" not found'
);
\envtesting\Assert::true(
	\envtesting\Check::cls('wikidi_webDataSearch_categorizer_wordProb_ClientFactory'),
	'Class "wikidi_webDataSearch_categorizer_wordProb_ClientFactory" not found'
);


