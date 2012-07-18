<?php
namespace envtesting\output;

use envtesting\Suite;

/**
 * Generate HTML output of envtesting test
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
final class Html {

	/**
	 * Render HTML output
	 *
	 * @param Suite $suit
	 * @return void
	 */
	public static function render(Suite $suit) {
		$total = $error = $warning = $exception = $ok = $disabled = 0;
		$filter = $suit->getFilter();
		require __DIR__ . '/layout.phtml';
	}

	/**
	 * @param $query
	 * @param bool $add
	 * @return string
	 */
	public static function link($query = null, $add = false) {
		$url = isset($_SERVER['REQUEST_URI']) ? trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') : null;
		if ($add && isset($_SERVER['QUERY_STRING'])) $query .= '&' . $_SERVER['QUERY_STRING'];
		parse_str($query, $params);
		return ($params) ? $url . '?' . http_build_query($params) : $url;
	}

}