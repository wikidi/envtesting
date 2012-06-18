<?php
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::cls('wikidi_webDataSearch_categorizer_wordProb_Client') &&
		\envtesting\Check::cls('wikidi_webDataSearch_categorizer_wordProb_ClientFactory'),
	'Class wikidi_webDataSearch_categorizer_wordProb_Client or wikidi_webDataSearch_categorizer_wordProb_ClientFactory not found'
);

