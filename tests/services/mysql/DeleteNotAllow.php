<?php
namespace tests\services\mysql;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class DeleteNotAllow extends Core {

	/**
	 * @throws \envtesting\Error
	 */
	public function __invoke($table = '_envtesting_', $coll = 'data', $value = __CLASS__) {
		$error = false;

		try {
			$this->tryDeleteData($table, $coll, $value);
		} catch (\envtesting\Error $e) {
			$error = true;
		}

		if (!$error) {
			throw new \envtesting\Error('User ' . $this->user . ' can delete.' . PHP_EOL . $this);
		}

	}
}