<?php
namespace src\envtesting;

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
	 * Use file for checking result
	 *
	 * @param $file
	 * @return closure
	 */
	public static function useFile($file, $dir = __DIR__) {
		return function () use ($file, $dir) {
			require_once $dir . DIRECTORY_SEPARATOR . $file;
		};
	}
}