<?php
namespace tests\services\mysql;
require_once __DIR__ . '/Connection.php';

/**
 * Contains core
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
abstract class Core extends Connection {

	const DELETE = 'DELETE FROM `%s` WHERE %s = "%s";';
	const INSERT = 'INSERT INTO `%s` SET %s = "%s";';

	/**
	 * @throws \envtesting\Error
	 */
	public function tryDeleteData($table, $coll, $value) {
		$sql = sprintf(self::DELETE, $table, $coll, mysql_real_escape_string($value));

		try {
			$rowCount = $this->getConnection()->exec($sql);
			$info = $this->connection->errorInfo();

			if ($rowCount != 1 && $info[0] == '00000') {
				throw new \envtesting\Error(' Delete operation failed: Test data not found.' . PHP_EOL . $this);
			} else if ($info[0] != '00000') {
				throw new \envtesting\Error('Delete operation failed! Error #no: ' . $info[0] . ' (' . $info[2] . ') ' . PHP_EOL . $this);
			}

		} catch (\PDOException $e) {
			throw new \envtesting\Error('Delete operation failed: ' . $e->getMessage());
		}
	}

	/**
	 * @throws \envtesting\Error
	 */
	public function tryInsertData($table, $coll, $value) {
		$sql = sprintf(self::INSERT, $table, $coll, mysql_real_escape_string($value));
		try {
			$rowCount = $this->getConnection()->exec($sql);
			$info = $this->connection->errorInfo();
			//var_dump($rowCount);
			//var_dump($info);

			if ($rowCount != 1 && $info[0] == '00000') {
				throw new \envtesting\Error(' Insert operation failed: Test data not found.' . PHP_EOL . $this);
			} else if ($info[0] != '00000') {
				throw new \envtesting\Error('Insert operation failed! Error #no: ' . $info[0] . ' (' . $info[2] . ') ' . PHP_EOL . $this);
			}


		} catch (\PDOException $e) {
			throw new \envtesting\Error('Insert operation failed: ' . $e->getMessage());
		}
	}

}