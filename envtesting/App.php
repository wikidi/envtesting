<?php
namespace envtesting;

/**
 * Envtesting is fast simple and easy to use environment testing written in PHP.
 * Can check library, services and services response.
 *
 * Produce console, HTML or CSV output.
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 * @license MIT
 */
class App {

	/** @var string */
	public static $root = __DIR__;

	/**
	 * ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	 * Generate header
	 * ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	 *
	 * @param string $text
	 * @param string $ch
	 * @return string
	 */
	public static function header($text, $ch = ':') {
		return str_repeat($ch, 80) . PHP_EOL .
		str_pad($text, 80, ' ', STR_PAD_BOTH) . PHP_EOL .
		str_repeat($ch, 80) . PHP_EOL;
	}
}
