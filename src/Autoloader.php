<?php
namespace src\envtesting;

/**
 * Class autoloading and app tools
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
final class Autoload {

	/** @var null|boolean */
	private static $update = true;
	/** @var bool */
	private static $init = true;
	/** @var array */
	private static $paths = array();

	/**
	 * Add path for autoloading
	 *
	 * @static
	 * @param string $path
	 */
	public static function addPath($path) {
		self::$paths[] = $path;
		self::$update = true;
	}

	/**
	 * @static
	 * @param string $className
	 * @return bool
	 */
	public static function cls($className) {
		if (self::$init) {
			self::$paths[] = get_include_path();
			self::$paths[] = dirname(__DIR__); // envtesting home dir
			self::$init = false; // init just once
		}

		// update path
		if (self::$update) {
			set_include_path(implode(PATH_SEPARATOR, self::$paths));
			self::$update = false;
		}

		$className = ltrim($className, '\\');
		$fileName = '';
		if ($lastNsPos = strripos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR; //namespace replace
		}
		$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php'; //pear replace on classname only
		return (bool)@include_once $fileName;
	}
}

spl_autoload_register(array('envtesting\Autoload', 'cls'));