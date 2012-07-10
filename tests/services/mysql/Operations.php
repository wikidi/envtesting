<?php
namespace tests\services\mysql;

/**
 * Test common MySql operation
 *
 * @method deleteAllow()
 * @method deleteNotAllow()
 * @method insertAllow()
 * @method insertNotAllow()
 * @method updateAllow()
 * @method updateNotAllow()
 *
 * @see http://dev.mysql.com/doc/refman/5.0/en/error-messages-server.html
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Operations extends Core {

	/** @var string */
	public $table = '_envtesting_';

	/** @var string */
	public $coll = 'data';

	/**
	 * Call all private methods
	 *
	 * @param string $name
	 * @param array $args
	 * @return string
	 * @throws \envtesting\Error
	 */
	public function __call($name, $args) {
		$this->getConnection()->beginTransaction();
		try {
			$result = call_user_func_array(array($this, $name), $args);
			$this->getConnection()->rollBack();
			return $result;
		} catch (\envtesting\Error $e) {
			$this->getConnection()->rollBack();
			throw $e;
		}
	}

	// -------------------------------------------------------------------------------------------------------------------
	// DELETE
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Check if user can delete data
	 *
	 * @throws \envtesting\Error
	 */
	private function deleteAllow() {
		$this->tryDeleteData($this->table, $this->coll, __FUNCTION__);
		return 'DELETE allow to user ' . $this->user . PHP_EOL . $this;
	}

	/**
	 * Check if user can't delete data
	 *
	 * @throws \envtesting\Error
	 */
	private function deleteNotAllow() {
		try {
			$this->tryDeleteData($this->table, $this->coll, __FUNCTION__);
		} catch (\envtesting\Error $e) {
			if ($this->lastErrorNumber() == 1142) {
				return 'DELETE denied to user ' . $this->user . PHP_EOL . $this;
			}
		}
		throw new \envtesting\Error('DELETE allow to user ' . $this->user . PHP_EOL . $this);
	}

	// -------------------------------------------------------------------------------------------------------------------
	// INSERT
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Check if iser can't insert data
	 *
	 * @throws \envtesting\Error
	 */
	private function insertAllow() {
		$this->tryInsertData($this->table, $this->coll, __FUNCTION__);
		return 'INSERT allow to user ' . $this->user . PHP_EOL . $this;
	}

	/**
	 * Check if iser can't insert data
	 *
	 * @throws \envtesting\Error
	 */
	private function insertNotAllow() {
		try {
			$this->tryInsertData($this->table, $this->coll, __FUNCTION__);
		} catch (\envtesting\Error $e) {
			if ($this->lastErrorNumber() == 1142) {
				return 'INSERT denied to user ' . $this->user . PHP_EOL . $this;
			}
		}

		throw new \envtesting\Error('INSERT allow to user ' . $this->user . PHP_EOL . $this);
	}

	/**
	 * @return string
	 */
	private function selectAllow() {
		$this->trySelectData($this->table, $this->coll, __FUNCTION__);
		return 'SELECT allow to use ' . $this->user . PHP_EOL . $this;
	}

}