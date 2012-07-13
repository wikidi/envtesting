<?php
namespace envtesting;
/**
 * Get all internal PHP errors, notices and warnings and throws them as Exceptions
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Throws {

	/**
	 * Throws all errors
	 */
	static function allErrors() {
		set_error_handler(array('\\envtesting\\Throws', 'handleError'), E_ALL & ~E_DEPRECATED);
	}

	/**
	 * @param int $code
	 * @param string $text
	 * @param string $file
	 * @param string $line
	 * @param array $context
	 * @throws \Exception
	 */
	static function handleError($code, $text, $file, $line, $context) {
		if (error_reporting() == 0) return;
		throw new \Exception($text . ' in file ' . $file . ' on line ' . $line, $code);
	}

	/**
	 * Restore error hand
	 */
	static function nothing() {
		restore_error_handler();
	}
}