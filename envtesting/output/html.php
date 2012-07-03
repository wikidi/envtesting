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
	 * @param string $title
	 * @return void
	 */
	public static function render(Suit $suit) {
		$total = $error = $warning = $exception = $ok = $disabled = 0;
		$filter = $suit->getFilter();

		require __DIR__ . '/layout.phtml';
	}

}