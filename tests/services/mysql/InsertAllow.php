<?php
namespace tests\services\mysql;

/**
 * Try insert test data to database
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class InsertAllow extends Core {

	/**
	 * @param string $table
	 * @param string $coll
	 * @param string $value
	 * @throws \envtesting\Error
	 */
	public function __invoke($table = '_envtesting_', $coll = 'data', $value = __CLASS__) {
		$this->tryInsertData($table, $coll, $value);
	}

}