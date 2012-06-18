<?php
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::cls('MongoDB'),
	'MongoDB class not found'
);