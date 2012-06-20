<?php
namespace envtesting\tests;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check if fann server is loaded
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */

\envtesting\Assert::true(
	\envtesting\Check::cls('fann\clients\ServerProxy'),
	'Fann class fann\clients\ServerProxy not exists'
);