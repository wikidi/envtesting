<?php
namespace envtesting\output;

use envtesting\Suit;

/**
 * Generate HTML output of envtesting test
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
final class Html {

	/**
	 * Render HTML output
	 *
	 * @param Suit $suit
	 * @return void
	 */
	public static function render(Suit $suit) {
		$total = $error = $warning = $exception = $ok = $disabled = 0;
		$path = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH): '/';
		$query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
		$filter = $suit->getFilter();
		require __DIR__ . '/layout.phtml';
	}

}