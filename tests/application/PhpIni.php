<?php
namespace tests\application;
use envtesting\Assert;
use envtesting\Check;

/**
 * Check common
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
final class PhpIni {

	public static function logErrors() {
		Assert::true(Check::ini('log_errors', '1'), 'log_errors is OFF !!!');
		return 'log_errors is ON';
	}

	public static function displayErrors() {
		Assert::true(Check::ini('display_errors', '1'), 'display_errors is OFF !!!');
		return 'display_errors is ON';
	}

	public static function notDisplayErrors() {
		Assert::true(Check::ini('display_errors', '0'), 'display_errors is ON !!!');
		return 'display_errors is OFF';
	}
}