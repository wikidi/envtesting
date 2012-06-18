<?php
require_once __DIR__ . '/../../Envtesting.php';

\envtesting\Assert::true(
	\envtesting\Check::cls('fann\clients\ServerProxy'),
	'Fann class fann\clients\ServerProxy not exists'
);