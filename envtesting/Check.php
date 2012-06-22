<?php
namespace envtesting;

/**
 * Basic envtesting check
 *
 * @author Roman Ozana <roman@wikidi.com>
 */
class Check {

	/**
	 * Check if library and function exists
	 *
	 * @param string $extensionName
	 * @param string $infoFunction
	 * @return bool|string
	 */
	public static function lib($extensionName, $infoFunction = '') {
		return extension_loaded($extensionName) && ($infoFunction === '' || function_exists($infoFunction));
	}

	/**
	 * Check if class exists
	 *
	 * @param string $className
	 * @return bool
	 */
	public static function cls($className) {
		return class_exists($className);
	}

	/**
	 * Check php.ini
	 *
	 * - check value and return boolean response if same or
	 * - return value of variable
	 *
	 * @param mixed $variable
	 * @param null|mixed $value
	 * @return bool
	 */
	public static function ini($variable, $value = null) {
		return ($value === null) ? ini_get($variable) : $value === ini_get($variable);
	}

	/**
	 * Use PHP file for checking result
	 *
	 * @param string $file
	 * @param string $dir
	 * @return mixed callback
	 */
	public static function file($file, $dir = __DIR__) {
		return function () use ($file, $dir) {
			include $dir . DIRECTORY_SEPARATOR . $file;
		};
	}
}