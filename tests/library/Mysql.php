<?php
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::lib('mysql', 'mysql_info'),
	'mysql library not found'
);