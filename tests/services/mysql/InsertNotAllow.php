<?php
namespace tests\services\mysql;

/**
 * Try insert test data to database
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class InsertNotAllow extends Core {

	/**
	 * @param string $table
	 * @param string $coll
	 * @param string $value
	 * @throws \envtesting\Error
	 */
	public function __invoke($table = '_envtesting_', $coll = 'data', $value = __CLASS__) {
		try {
			$this->tryInsertData($table, $coll, $value);
		} catch (\envtesting\Error $e) {
			return; // error is OK
		}

		throw new \envtesting\Error('User ' . $this->user . ' can insert data' . PHP_EOL . $this);
	}

}