<?php
namespace tests\application\mysql;
use tests\services\mysql\Connection;

/**
 * Contains basic MySQL operations DELETE, UPDATE, INSERT, SELECT
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
abstract class Core extends Connection {

	const DELETE = 'DELETE FROM `%s` WHERE %s = %s;';

	/**
	 * @param string $table
	 * @param string $coll
	 * @param string $value
	 * @throws \envtesting\Error
	 */
	public function tryDeleteData($table, $coll, $value) {
		try {
			$sql = sprintf(self::DELETE, $table, $coll, $this->esc($value));
			$rowCount = $this->getConnection()->exec($sql);
			$this->processInfo('DELETE', $rowCount == 1, $value);
		} catch (\PDOException $e) {
			throw new \envtesting\Error('DELETE operation failed: ' . $e->getMessage());
		}
	}

	const INSERT = 'INSERT INTO `%s` SET %s = %s;';

	/**
	 * @param string $table
	 * @param string $coll
	 * @param string $value
	 * @throws \envtesting\Error
	 */
	public function tryInsertData($table, $coll, $value) {
		try {
			$sql = sprintf(self::INSERT, $table, $coll, $this->esc($value));
			$rowCount = $this->getConnection()->exec($sql);
			$this->processInfo('INSERT', $rowCount == 1, $value);
		} catch (\PDOException $e) {
			throw new \envtesting\Error('INSERT operation failed: ' . $e->getMessage());
		}
	}

	const UPDATE = 'UPDATE `%s` SET `%s` = %s WHERE `%s` = %s LIMIT 1;';

	/**
	 * @param string $table
	 * @param string $coll
	 * @param string $value
	 * @throws \envtesting\Error
	 */
	public function tryUpdateData($table, $coll, $value) {
		try {
			$sql = sprintf(self::UPDATE, $table, $coll, $this->esc($value), $coll, $this->esc($value));
			$rowCount = $this->getConnection()->exec($sql);
			$this->processInfo('UPDATE', $rowCount == 1, $value);
		} catch (\PDOException $e) {
			throw new \envtesting\Error('UPDATE operation failed: ' . $e->getMessage());
		}
	}

	const SELECT = 'SELECT * FROM `%s` WHERE `%s` = %s LIMIT 1;';

	/**
	 * @param string $table
	 * @throws \envtesting\Error
	 */
	public function trySelectData($table, $coll, $value) {
		try {
			$sql = sprintf(self::SELECT, $table, $coll, $this->esc($value));
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
	 * @param boolean $data true when data arrives
	 * @param mixed|string $value
	 * @throws \envtesting\Error
	 * @see http://dev.mysql.com/doc/refman/5.0/en/error-messages-server.html
	 */
	private function processInfo($type, $data, $value) {
		if (!$data && $this->lastErrorState() == '00000') {
			throw new \envtesting\Warning($type . ' operation failed: Test data "' . $value . '" not found.' . PHP_EOL . $this);
		} else if ($this->lastErrorState() != '00000') {
			throw new \envtesting\Error($type . ' operation failed! Error #no: ' . $this->lastErrorNumber(
			) . ' (' . $this->lastErrorMessage() . ') ' . PHP_EOL . $this);
		}
	}

	/**
	 * Return last error number
	 *
	 * @return int
	 */
	public function lastErrorNumber() {
		$info = $this->getConnection()->errorInfo();
		return (int)$info[1];
	}

	/**
	 * Return last error state
	 *
	 * @return int
	 */
	public function lastErrorState() {
		$info = $this->getConnection()->errorInfo();
		return (int)$info[0];
	}

	/**
	 * Return last error message
	 *
	 * @return string
	 */
	public function lastErrorMessage() {
		$info = $this->getConnection()->errorInfo();
		return (string)$info[2];
	}

	/**
	 * @return string
	 */
	public function esc($value) {
		return $this->getConnection()->quote($value);
	}

}