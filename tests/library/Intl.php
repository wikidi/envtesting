<?php
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::lib('intl', 'intl_error_name'),
	'intl library not found'
);