<?php
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::lib('simplexml'),
	'Simplexml library not found'
);