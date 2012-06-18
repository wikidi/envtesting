<?php
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::lib('gettext', 'gettext'),
	'Gettext library not found'
);