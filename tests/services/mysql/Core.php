<?php
namespace tests\services\mysql;
require_once __DIR__ . '/Connection.php';

/**
 * Contains core
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Core extends Connection {

	const DELETE = 'DELETE FROM `%s` WHERE %s = "%s";';

	/**
	 * @param string $table
	 * @param string $coll
	 * @param string $value
	 * @throws \envtesting\Error
	 */
	public function tryDeleteData($table, $coll, $value) {
		$sql = sprintf(self::DELETE, $table, $coll, mysql_real_escape_string($value));
		try {
			$rowCount = $this->getConnection()->exec($sql);
			$this->processInfo('DELETE', $rowCount == 1, $value);
		} catch (\PDOException $e) {
			throw new \envtesting\Error('DELETE operation failed: ' . $e->getMessage());
		}
	}

	const INSERT = 'INSERT INTO `%s` SET %s = "%s";';

	/**
	 * @param string $table
	 * @param string $coll
	 * @param string $value
	 * @throws \envtesting\Error
	 */
	public function tryInsertData($table, $coll, $value) {
		$sql = sprintf(self::INSERT, $table, $coll, mysql_real_escape_string($value));
		try {
			$rowCount = $this->getConnection()->exec($sql);
			$this->processInfo('INSERT', $rowCount == 1, $value);
		} catch (\PDOException $e) {
			throw new \envtesting\Error('INSERT operation failed: ' . $e->getMessage());
		}
	}

	const UPDATE = 'UPDATE INTO `%s` SET %s = "%s";';

	/**
	 * @param string $table
	 * @param string $coll
	 * @param string $value
	 * @throws \envtesting\Error
	 */
	public function tryUpdateData($table, $coll, $value) {

	}

	const SELECT = 'SELECT * FROM `%s` WHERE %s = "%s" LIMIT 1;';

	/**
	 * @param string $table
	 * @throws \envtesting\Error
	 */
	public function trySelectData($table, $coll, $value) {
		$sql = sprintf(self::SELECT, $table, $coll, mysql_real_escape_string($value));
		try {
			$stm = $this->getConnection()->query($sql);
			if ($stm) $row = $stm->fetch();
			$this->processInfo('SELECT', isset($row[$coll]), $value);
		} catch (\PDOException $e) {
			throw new \envtesting\Error('SELECT operation failed: ' . $e->getMessage());
		}
	}

	/**
	 * Process error info
	 *
	 * @param string $type
	 * @param boolean $data
	 * @param mixed $value
	 * @throws \envtesting\Error
	 */
	private function processInfo($type, $data, $value) {
		$info = $this->getConnection()->errorInfo();

		if (!$data && $info[0] == '00000') {
			throw new \envtesting\Warning($type . ' operation failed: Test data "' . $value . '" not found.' . PHP_EOL . $this);
		} else if ($info[0] != '00000') {
			throw new \envtesting\Error($type . ' operation failed! Error #no: ' . $info[1] . ' (' . $info[2] . ') ' . PHP_EOL . $this);
		}
	}

	/**
	 * @return int
	 */
	public function lastErrorNumber() {
		$info = $this->getConnection()->errorInfo();
		return (int)$info[1];
	}

}