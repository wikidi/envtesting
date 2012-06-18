<?php
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::lib('gd', 'gd_info'),
	'GD library not found'
);