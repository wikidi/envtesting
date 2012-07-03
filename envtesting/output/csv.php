<?php
namespace envtesting\output;
use envtesting\Suit;

/**
 * Generate CSV output of envtesting test
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
final class Csv {

	/**
	 * Render CSV output
	 *
	 * @param Suit $suit
	 * @return void
	 */
	public static function render(Suit $suit) {
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename=file.csv');
		header('Pragma: no-cache');
		header('Expires: 0');

		foreach ($suit as $group => $tests) {
			foreach ($tests as $order => $test/** @var \envtesting\Test $test */) {
				$options = ($test->getOptions() ? '<br/>' . json_encode($test->getOptions()) : '');
				if ($test->isEnabled()) {
					$data = array(
						$test->getStatus(),
						$group . ':' . $test->getName(),
						$test->getNotice(),
						$test->getType(),
						$test->isOk() ? 'OK' : $test->getStatusMessage() . $options,
						$order,
					);
					echo addslashes(implode(', ', $data)) . PHP_EOL;
				}
			}
		}
	}


}