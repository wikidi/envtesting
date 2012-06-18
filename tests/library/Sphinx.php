<?php
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::cls('SphinxClient'),
	'SphinxClient class not found'
);

