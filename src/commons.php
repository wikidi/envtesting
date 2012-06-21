<?php
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
spl_autoload_register(
	function ($className) {
		$className = ltrim($className, '\\');
		$fileName = '';
		$namespace = '';
		if ($lastNsPos = strripos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR; //namespace replace
		}
		$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php'; //pear replace on classname only
		return (bool)@include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . $fileName;
	}
);