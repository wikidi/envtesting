<?php
namespace envtesting\output;
use envtesting\Suite;

/**
 * Generate CSV output of envtesting tests
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
final class Csv {

	/**
	 * Render CSV output
	 *
	 * @param Suite $suite
	 * @return void
	 */
	public static function render(Suite $suite) {
		$name = preg_replace('#[^a-z0-9]+#i', '-', strtolower($suite->getName())); // sanitize filename
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename=' . trim($name, '-') . '.env.csv');
		header('Pragma: no-cache');
		header('Expires: 0');

		foreach ($suite as $group => $tests) {
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