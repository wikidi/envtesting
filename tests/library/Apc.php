<?php
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::lib('apc', 'apc_inc'),
	'APC not found'
);