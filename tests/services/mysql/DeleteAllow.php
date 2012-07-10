<?php
namespace tests\services\mysql;

/**
 * Delete is allow for current connection and user
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class DeleteAllow extends Core {

	/**
	 * @throws \envtesting\Error
	 */
	public function __invoke($table = '_envtesting_', $coll = 'data', $value = __CLASS__) {
		try {
			$this->tryDeleteData($table, $coll, $value);
			$this->tryInsertData($table, $coll, $value); // prepare to next run
		} catch (\Exception $e) {
			$this->tryInsertData($table, $coll, $value); // finalize
			throw $e;
		}
	}

}