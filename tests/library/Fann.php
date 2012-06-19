<?php
namespace envtesting\tests;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check if Fann server class loaded
 */
\envtesting\Assert::true(
	\envtesting\Check::cls('fann\clients\ServerProxy'),
	'Fann class fann\clients\ServerProxy not exists'
);