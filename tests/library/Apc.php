<?php
namespace envtesting\tests;
require_once __DIR__ . '/../../Envtesting.php';

/**
 * Check if APC library is loaded
 * @see http://php.net/manual/en/book.apc.php
 */
\envtesting\Assert::true(
	\envtesting\Check::lib('apc', 'apc_inc'),
	'APC not found'
);