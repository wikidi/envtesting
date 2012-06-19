<?php
namespace envtesting\tests;
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::lib('curl', 'curl_init'),
	'Curl not found'
);